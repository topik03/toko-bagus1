<?php

// app/Models/Cart.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    protected $fillable = ['user_id', 'session_id'];

    // Relasi ke user (jika login)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke cart items
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // Hitung total items
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    // Hitung total harga
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    // Cari atau buat cart berdasarkan session/user
    // Method ini harus dipanggil dari Controller, bukan dari Model langsung
    public static function getOrCreateCart()
    {
        // Pakai Auth::check() dan Auth::id() bukan auth()->check()
        if (Auth::check()) {
            // User login: cari berdasarkan user_id
            return self::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            // Guest: cari berdasarkan session_id
            $sessionId = Session::getId();
            return self::firstOrCreate(['session_id' => $sessionId]);
        }
    }

    // Atau buat method yang menerima parameter
    public static function getOrCreateCartForUser($userId = null, $sessionId = null)
    {
        if ($userId) {
            return self::firstOrCreate(['user_id' => $userId]);
        } else {
            return self::firstOrCreate(['session_id' => $sessionId ?? Session::getId()]);
        }
    }
}
