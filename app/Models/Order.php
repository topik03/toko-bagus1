<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'subtotal',
        'shipping_cost',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
        'tracking_number',
        'shipping_carrier'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Generate order number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Alias untuk address (karena Anda pakai shipping_address langsung di order)
     */
    public function address()
    {
        // Return virtual relationship untuk kompatibilitas
        return (object) [
            'full_address' => $this->shipping_address,
            'city' => $this->shipping_city,
            'postal_code' => $this->shipping_postal_code,
            'phone' => $this->customer_phone,
            'name' => $this->customer_name,
        ];
    }

    /**
     * Untuk kompatibilitas dengan kode yang membutuhkan address_id
     */
    public function getAddressIdAttribute()
    {
        return null; // Karena address disimpan langsung di order
    }

    // Status labels - Payment Status
    public function getPaymentStatusLabelAttribute()
    {
        $statuses = [
            'pending' => ['label' => 'Menunggu Pembayaran', 'color' => 'yellow'],
            'paid' => ['label' => 'Sudah Dibayar', 'color' => 'green'],
            'failed' => ['label' => 'Gagal', 'color' => 'red'],
        ];

        return $statuses[$this->payment_status] ?? ['label' => 'Unknown', 'color' => 'gray'];
    }

    // Status labels - Order Status
    public function getOrderStatusLabelAttribute()
    {
        $statuses = [
            'pending' => ['label' => 'Menunggu', 'color' => 'yellow'],
            'processing' => ['label' => 'Diproses', 'color' => 'blue'],
            'shipped' => ['label' => 'Dikirim', 'color' => 'purple'],
            'delivered' => ['label' => 'Sampai', 'color' => 'green'],
            'completed' => ['label' => 'Selesai', 'color' => 'green'],
            'cancelled' => ['label' => 'Dibatalkan', 'color' => 'red'],
            'refunded' => ['label' => 'Dikembalikan', 'color' => 'red'],
        ];

        return $statuses[$this->order_status] ?? ['label' => 'Unknown', 'color' => 'gray'];
    }

    /**
     * Alias untuk status (agar kompatibel dengan kode yang pakai 'status' bukan 'order_status')
     */
    public function getStatusAttribute()
    {
        return $this->order_status;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['order_status'] = $value;
    }

    /**
     * Alias untuk total_amount (agar kompatibel dengan kode yang pakai 'total_amount')
     */
    public function getTotalAmountAttribute()
    {
        return $this->total;
    }

    public function setTotalAmountAttribute($value)
    {
        $this->attributes['total'] = $value;
    }

    /**
     * Untuk kompatibilitas dengan kode yang butuh completed_at
     */
    public function getCompletedAtAttribute()
    {
        if ($this->order_status === 'delivered' || $this->order_status === 'completed') {
            return $this->updated_at;
        }
        return null;
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute()
    {
        $address = $this->shipping_address;
        if ($this->shipping_city) {
            $address .= ', ' . $this->shipping_city;
        }
        if ($this->shipping_postal_code) {
            $address .= ' ' . $this->shipping_postal_code;
        }
        return $address;
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('order_status', $status);
    }

    /**
     * Scope untuk pesanan pending
     */
    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    /**
     * Scope untuk pesanan completed
     */
    public function scopeCompleted($query)
    {
        return $query->where('order_status', 'completed')
                    ->orWhere('order_status', 'delivered');
    }
}
