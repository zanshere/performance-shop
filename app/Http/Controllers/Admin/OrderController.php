<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Filter by order status
        if ($request->has('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Date range filter
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(15);

        // Statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('payment_status', 'pending')->count(),
            'paid' => Order::where('payment_status', 'paid')->count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'shipped' => Order::where('order_status', 'shipped')->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);

        // Order status options
        $statusOptions = [
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        // Payment status options
        $paymentStatusOptions = [
            'pending' => 'Menunggu',
            'paid' => 'Dibayar',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
        ];

        return view('admin.orders.show', compact('order', 'statusOptions', 'paymentStatusOptions'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $order->load(['items.product', 'user']);

        $statusOptions = [
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $paymentStatusOptions = [
            'pending' => 'Menunggu',
            'paid' => 'Dibayar',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
        ];

        return view('admin.orders.edit', compact('order', 'statusOptions', 'paymentStatusOptions'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,expired',
            'shipping_service' => 'nullable|string|max:100',
            'shipping_note' => 'nullable|string|max:500',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        // If order is being shipped, set shipped_at
        if ($validated['order_status'] == 'shipped' && $order->order_status != 'shipped') {
            $validated['shipped_at'] = now();
        }

        // If order is delivered, set delivered_at
        if ($validated['order_status'] == 'delivered' && $order->order_status != 'delivered') {
            $validated['delivered_at'] = now();
        }

        // If order is cancelled, set cancelled_at
        if ($validated['order_status'] == 'cancelled' && $order->order_status != 'cancelled') {
            $validated['cancelled_at'] = now();

            // Return stock if order was paid
            if ($order->payment_status == 'paid') {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }
        }

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    /**
     * Update order status via AJAX
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->order_status;
        $order->order_status = $request->status;

        // Set timestamps based on status change
        if ($request->status == 'shipped' && $oldStatus != 'shipped') {
            $order->shipped_at = now();
        }

        if ($request->status == 'delivered' && $oldStatus != 'delivered') {
            $order->delivered_at = now();
        }

        if ($request->status == 'cancelled' && $oldStatus != 'cancelled') {
            $order->cancelled_at = now();

            // Return stock if order was paid
            if ($order->payment_status == 'paid') {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
            'order' => $order
        ]);
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        // Only allow deletion of pending orders
        if ($order->payment_status != 'pending') {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Hanya pesanan dengan status pending yang dapat dihapus.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

    /**
     * Export orders
     */
    public function export(Request $request)
    {
        $orders = Order::with(['items', 'user'])
            ->when($request->has('date_from'), function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->has('date_to'), function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->has('status'), function($q) use ($request) {
                $q->where('order_status', $request->status);
            })
            ->get();

        // In a real application, generate CSV or Excel file
        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Data pesanan siap diunduh.'
        ]);
    }

    /**
     * Get order statistics
     */
    public function statistics(Request $request)
    {
        $period = $request->get('period', 'monthly');

        $stats = DB::table('orders')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as revenue')
            ->where('payment_status', 'paid')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
