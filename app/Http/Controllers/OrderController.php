<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display user's orders
     */
    public function index(Request $request)
    {
        $query = Order::query();

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            // For guest users, we need to implement session-based order tracking
            return redirect()->route('login')->with('message', 'Silakan login untuk melihat pesanan Anda');
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('order_status', $request->status);
        }

        // Search by order code
        if ($request->has('search')) {
            $query->where('order_code', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        $statuses = [
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return view('orders.index', compact('orders', 'statuses'));
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        // Check authorization
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // For guest users, check session or email verification

        // Get snap token from session if available
        $snapToken = session('snap_token');
        $redirectUrl = session('redirect_url');

        return view('orders.show', compact('order', 'snapToken', 'redirectUrl'));
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Check authorization
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if order can be cancelled
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $order->update([
                'order_status' => 'cancelled',
                'payment_status' => 'failed',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->reason,
            ]);

            // Return stock if order was paid but cancelled
            if ($order->isPaid()) {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
            }

            return back()->with('success', 'Pesanan berhasil dibatalkan');

        } catch (\Exception $e) {
            Log::error('Order cancellation error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan');
        }
    }

    /**
     * Track order status
     */
    public function track(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string',
            'email' => 'required|email',
        ]);

        $order = Order::where('order_code', $request->order_code)
                     ->where('customer_email', $request->email)
                     ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan. Periksa kode pesanan dan email Anda.');
        }

        return view('orders.track', compact('order'));
    }

    /**
     * Reorder functionality
     */
    public function reorder($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        // Check authorization
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $cart = session()->get('cart', []);

        foreach ($order->items as $item) {
            $product = $item->product;

            if ($product && $product->is_active && $product->stock > 0) {
                $quantity = min($item->quantity, $product->stock);

                if (isset($cart[$product->id])) {
                    $cart[$product->id]['quantity'] += $quantity;
                } else {
                    $cart[$product->id] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->final_price,
                        'original_price' => $product->price,
                        'discount_price' => $product->discount_price,
                        'quantity' => $quantity,
                        'image' => $product->images ? json_decode($product->images, true)[0] : null,
                        'stock' => $product->stock,
                        'brand' => $product->brand,
                        'category' => $product->category,
                    ];
                }
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Download invoice
     */
    public function invoice($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        // Check authorization
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if order is paid
        if (!$order->isPaid()) {
            return back()->with('error', 'Invoice hanya tersedia untuk pesanan yang sudah dibayar');
        }

        // In a real application, generate PDF invoice
        // For now, we'll just show a view

        return view('orders.invoice', compact('order'));
    }
}
