<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Display the cart page
     */
    public function index(Request $request)
    {
        $cart = $this->getCartData();

        return view('cart.index', compact('cart'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock,
            ], 422);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->final_price,
                'original_price' => $product->price,
                'discount_price' => $product->discount_price,
                'quantity' => $request->quantity,
                'image' => $product->images ? json_decode($product->images, true)[0] : null,
                'stock' => $product->stock,
                'brand' => $product->brand,
                'category' => $product->category,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->getCartTotal(),
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $product = Product::find($id);

            // Check stock
            if ($product && $product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock,
                ], 422);
            }

            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui',
                'cart_count' => $this->getCartCount(),
                'cart_total' => $this->getCartTotal(),
                'item_subtotal' => $cart[$id]['price'] * $cart[$id]['quantity'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan di keranjang',
        ], 404);
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang',
                'cart_count' => $this->getCartCount(),
                'cart_total' => $this->getCartTotal(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan di keranjang',
        ], 404);
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan',
            'cart_count' => 0,
            'cart_total' => 0,
        ]);
    }

    /**
     * Display checkout page
     */
    public function checkout(Request $request)
    {
        $cart = $this->getCartData();

        if (empty($cart['items'])) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong');
        }

        // Get shipping options (simulated)
        $shippingOptions = $this->getShippingOptions();

        return view('cart.checkout', compact('cart', 'shippingOptions'));
    }

    /**
     * Process checkout and create order
     */
    public function processCheckout(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'customer_address' => 'required|string',
                'customer_city' => 'required|string|max:100',
                'customer_province' => 'required|string|max:100',
                'customer_postal_code' => 'required|string|max:10',
                'shipping_service' => 'required|string',
                'shipping_note' => 'nullable|string',
                'payment_method' => 'required|string|in:bank_transfer,credit_card,ewallet',
            ]);

            $cart = $this->getCartData();

            if (empty($cart['items'])) {
                return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong');
            }

            // Check stock availability
            foreach ($cart['items'] as $item) {
                $product = Product::find($item['id']);
                if (!$product || $product->stock < $item['quantity']) {
                    return back()->with('error', 'Stok untuk ' . $item['name'] . ' tidak mencukupi')->withInput();
                }
            }

            // Get shipping cost
            $shippingCost = $this->calculateShippingCost(
                $request->shipping_service,
                $request->customer_city
            );

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'customer_city' => $request->customer_city,
                'customer_province' => $request->customer_province,
                'customer_postal_code' => $request->customer_postal_code,
                'subtotal' => $cart['subtotal'],
                'shipping_cost' => $shippingCost,
                'total' => $cart['subtotal'] + $shippingCost,
                'shipping_service' => $request->shipping_service,
                'shipping_note' => $request->shipping_note,
                'payment_method' => $request->payment_method,
                'midtrans_order_id' => 'MOTOR-' . time() . '-' . rand(1000, 9999),
            ]);

            // Create order items
            foreach ($cart['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'product_price' => $item['original_price'],
                    'product_discount_price' => $item['discount_price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // Create Midtrans transaction
            $midtransResult = $this->midtransService->createSnapTransaction($order);

            if (!$midtransResult['success']) {
                throw new \Exception('Failed to create payment transaction: ' . $midtransResult['message']);
            }

            // Save snap token
            $order->update([
                'midtrans_response' => ['snap_token' => $midtransResult['snap_token']],
            ]);

            // Clear cart
            session()->forget('cart');

            DB::commit();

            // Redirect to payment page
            return redirect()->route('orders.show', $order->id)
                ->with('snap_token', $midtransResult['snap_token'])
                ->with('redirect_url', $midtransResult['redirect_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat proses checkout: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Get cart data from session
     */
    private function getCartData()
    {
        $cart = session()->get('cart', []);

        $items = [];
        $subtotal = 0;

        foreach ($cart as $item) {
            $itemSubtotal = $item['price'] * $item['quantity'];
            $subtotal += $itemSubtotal;

            $items[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'original_price' => $item['original_price'],
                'discount_price' => $item['discount_price'],
                'quantity' => $item['quantity'],
                'subtotal' => $itemSubtotal,
                'image' => $item['image'],
                'stock' => $item['stock'],
                'brand' => $item['brand'],
                'category' => $item['category'],
                'has_discount' => $item['discount_price'] !== null,
            ];
        }

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'count' => count($items),
        ];
    }

    /**
     * Get cart items count
     */
    private function getCartCount()
    {
        $cart = session()->get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Get cart total
     */
    private function getCartTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    /**
     * Get shipping options (simulated)
     */
    private function getShippingOptions()
    {
        return [
            [
                'code' => 'jne_reg',
                'name' => 'JNE Regular',
                'description' => 'Estimasi 3-5 hari',
                'cost' => 15000,
            ],
            [
                'code' => 'jne_oke',
                'name' => 'JNE OKE',
                'description' => 'Estimasi 2-4 hari',
                'cost' => 20000,
            ],
            [
                'code' => 'jne_yes',
                'name' => 'JNE YES',
                'description' => 'Estimasi 1-2 hari',
                'cost' => 25000,
            ],
            [
                'code' => 'tiki_reg',
                'name' => 'TIKI Regular',
                'description' => 'Estimasi 3-6 hari',
                'cost' => 17000,
            ],
            [
                'code' => 'tiki_ons',
                'name' => 'TIKI ONS',
                'description' => 'Estimasi 1-2 hari',
                'cost' => 30000,
            ],
        ];
    }

    /**
     * Calculate shipping cost (simulated)
     */
    private function calculateShippingCost($serviceCode, $city)
    {
        $options = $this->getShippingOptions();

        foreach ($options as $option) {
            if ($option['code'] === $serviceCode) {
                return $option['cost'];
            }
        }

        return 20000; // Default shipping cost
    }

    /**
     * Midtrans notification handler
     */
    public function notification(Request $request)
    {
        $notification = $request->all();

        Log::info('Midtrans Notification:', $notification);

        $result = $this->midtransService->handleNotification($notification);

        if ($result['success']) {
            return response()->json(['message' => 'Notification processed successfully']);
        } else {
            return response()->json(['message' => 'Failed to process notification'], 500);
        }
    }
}
