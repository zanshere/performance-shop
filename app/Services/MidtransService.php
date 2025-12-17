<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        $this->setupConfig();
    }

    /**
     * Setup Midtrans configuration
     */
    private function setupConfig()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create Snap transaction
     */
    public function createSnapTransaction(Order $order)
    {
        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $order->midtrans_order_id,
                    'gross_amount' => (int) $order->total,
                ],
                'customer_details' => [
                    'first_name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone,
                    'shipping_address' => [
                        'first_name' => $order->customer_name,
                        'address' => $order->customer_address,
                        'city' => $order->customer_city,
                        'postal_code' => $order->customer_postal_code,
                        'country_code' => 'IDN',
                    ],
                ],
                'item_details' => $this->getItemDetails($order),
                'expiry' => [
                    'start_time' => date('Y-m-d H:i:s O'),
                    'unit' => 'hours',
                    'duration' => 24,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'redirect_url' => config('midtrans.snap_url') . '/snap/v2/vtweb/' . $snapToken,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to create payment transaction',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get item details for Midtrans
     */
    private function getItemDetails(Order $order)
    {
        $items = [];

        // Add order items
        foreach ($order->items as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int) $item->final_price,
                'quantity' => $item->quantity,
                'name' => $item->product_name,
            ];
        }

        // Add shipping cost as item
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        return $items;
    }

    /**
     * Handle Midtrans notification
     */
    public function handleNotification(array $notification)
    {
        try {
            $orderId = $notification['order_id'];
            $transactionStatus = $notification['transaction_status'];
            $fraudStatus = $notification['fraud_status'] ?? null;

            $order = Order::where('midtrans_order_id', $orderId)->firstOrFail();

            switch ($transactionStatus) {
                case 'capture':
                    if ($fraudStatus == 'challenge') {
                        $order->payment_status = 'challenge';
                        $order->order_status = 'processing';
                    } else if ($fraudStatus == 'accept') {
                        $order->payment_status = 'paid';
                        $order->paid_at = now();
                        $order->order_status = 'processing';

                        // Update stock
                        $this->updateStock($order);
                    }
                    break;

                case 'settlement':
                    $order->payment_status = 'paid';
                    $order->paid_at = now();
                    $order->order_status = 'processing';

                    // Update stock
                    $this->updateStock($order);
                    break;

                case 'pending':
                    $order->payment_status = 'pending';
                    break;

                case 'deny':
                case 'cancel':
                case 'expire':
                    $order->payment_status = 'failed';
                    $order->order_status = 'cancelled';
                    $order->cancelled_at = now();
                    break;
            }

            // Save midtrans response
            $order->midtrans_response = $notification;
            $order->midtrans_transaction_id = $notification['transaction_id'] ?? null;
            $order->save();

            return [
                'success' => true,
                'order' => $order,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Update product stock after payment
     */
    private function updateStock(Order $order)
    {
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->decrement('stock', $item->quantity);
            }
        }
    }

    /**
     * Check transaction status
     */
    public function checkStatus($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            return [
                'success' => true,
                'status' => $status,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
