<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'specifications',
        'price',
        'discount_price',
        'stock',
        'sku',
        'brand',
        'category',
        'images',
        'is_featured',
        'is_active',
        'weight',
        'compatibility',
    ];

    protected $casts = [
        'images' => 'array',
        'compatibility' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'weight' => 'decimal:2',
    ];

    /**
     * Accessor untuk harga diskon
     */
    protected function discountPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?? $this->price,
        );
    }

    /**
     * Accessor untuk harga akhir (setelah diskon)
     */
    protected function finalPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->discount_price ?? $this->price,
        );
    }

    /**
     * Accessor untuk persentase diskon
     */
    protected function discountPercentage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->discount_price
                ? round((($this->price - $this->discount_price) / $this->price) * 100)
                : 0,
        );
    }

    /**
     * Scope untuk produk aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk produk featured
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope untuk filter berdasarkan brand
     */
    public function scopeBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope untuk search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    /**
     * Scope untuk filter harga
     */
    public function scopePriceRange($query, $min, $max)
    {
        if ($min) {
            $query->where('price', '>=', $min);
        }
        if ($max) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    /**
     * Cek apakah stok tersedia
     */
    public function isInStock()
    {
        return $this->stock > 0;
    }

    /**
     * Cek apakah ada diskon
     */
    public function hasDiscount()
    {
        return $this->discount_price && $this->discount_price < $this->price;
    }
}
