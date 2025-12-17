<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'price',
        'discount_price',
        'features',
        'included_items',
        'compatible_bikes',
        'duration_days',
        'difficulty_level',
        'power_gain_percentage',
        'before_after_images',
        'is_featured',
        'is_active',
        'order_count',
        'view_count',
    ];

    protected $casts = [
        'features' => 'array',
        'included_items' => 'array',
        'compatible_bikes' => 'array',
        'before_after_images' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Accessor untuk harga akhir
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
     * Cek apakah ada diskon
     */
    public function hasDiscount()
    {
        return $this->discount_price && $this->discount_price < $this->price;
    }

    /**
     * Scope untuk package aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk package featured
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Format kategori untuk display
     */
    public function getCategoryLabelAttribute()
    {
        $categories = [
            'harian' => 'Harian',
            'sport' => 'Sport',
            'racing' => 'Racing',
            'custom' => 'Custom',
        ];

        return $categories[$this->category] ?? $this->category;
    }

    /**
     * Format difficulty level untuk display
     */
    public function getDifficultyLabelAttribute()
    {
        $levels = [
            'beginner' => 'Pemula',
            'intermediate' => 'Menengah',
            'expert' => 'Expert',
        ];

        return $levels[$this->difficulty_level] ?? $this->difficulty_level;
    }

    /**
     * Increment view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Increment order count
     */
    public function incrementOrderCount()
    {
        $this->increment('order_count');
    }
}
