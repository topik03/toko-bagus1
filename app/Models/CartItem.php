<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price'];

    // Relasi ke cart
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    // Relasi ke product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Hitung subtotal
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}
