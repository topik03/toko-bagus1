<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'discount_price',
        'stock', 'weight', 'unit', 'sku', 'category_id',
        'main_image', 'is_active', 'is_featured', 'is_best_seller',
        'views', 'sold_count', 'user_id'
    ];

    // Format harga
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Hitung harga diskon
    public function getDiscountedPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    // Cek apakah ada diskon
    public function getHasDiscountAttribute()
    {
        return !is_null($this->discount_price) && $this->discount_price < $this->price;
    }

    // Persentase diskon
    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) return 0;
        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    // RELATIONS
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

        public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get the first image or a placeholder.
     */
// Tambahkan accessor
public function getImageUrlAttribute()
{
    if ($this->images->count() > 0) {
        $imagePath = $this->images->first()->image_path;

        // Handle berbagai format path
        if (strpos($imagePath, 'http') === 0) {
            return $imagePath; // URL lengkap
        } elseif (strpos($imagePath, 'uploads/') === 0 || strpos($imagePath, 'storage/') === 0) {
            return asset($imagePath); // Path relatif
        } else {
            return asset('storage/' . $imagePath); // Default ke storage
        }
    }

    return asset('images/placeholder-product.png');
}

// Di view, cukup panggil:
// {{ $product->image_url }}
}
