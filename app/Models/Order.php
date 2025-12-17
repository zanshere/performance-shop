<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_city',
        'customer_province',
        'customer_postal_code',
        'subtotal',
        'shipping_cost',
        'total',
        'shipping_service',
        'shipping_note',
        'payment_method',
        'payment_status',
        'order_status',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_response',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Generate order code on creating
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_code = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        });
    }

    /**
     * Get the user that owns the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Accessor for formatted order code
     */
    protected function formattedOrderCode(): Attribute
    {
        return Attribute::make(
            get: fn () => '#' . $this->order_code,
        );
    }

    /**
     * Accessor for formatted total
     */
    protected function formattedTotal(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format($this->total, 0, ',', '.'),
        );
    }

    /**
     * Accessor for payment status badge
     */
    protected function paymentStatusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                $badges = [
                    'pending' => 'warning',
                    'paid' => 'success',
                    'failed' => 'danger',
                    'expired' => 'danger',
                ];

                return $badges[$this->payment_status] ?? 'secondary';
            },
        );
    }

    /**
     * Accessor for order status badge
     */
    protected function orderStatusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                $badges = [
                    'pending' => 'warning',
                    'processing' => 'info',
                    'shipped' => 'primary',
                    'delivered' => 'success',
                    'cancelled' => 'danger',
                ];

                return $badges[$this->order_status] ?? 'secondary';
            },
        );
    }

    /**
     * Check if order is pending payment
     */
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if order is paid
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->order_status, ['pending', 'processing'])
            && $this->payment_status === 'pending';
    }

    /**
     * Scope for user's orders
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
}
