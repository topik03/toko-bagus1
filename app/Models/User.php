<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @method HasMany orders()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'phone',        // TAMBAH INI
        'address',      // TAMBAH INI
        'city',         // TAMBAH INI
        'postal_code',  // TAMBAH INI
        'birthdate',
        'is_active',    // TAMBAH INI
    ];

    public function addresses()
{
    return $this->hasMany(Address::class);
}

public function defaultAddress()
{
    return $this->hasOne(Address::class)->where('is_default', true);
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
    ];


    // TAMBAHKAN RELATIONSHIP INI
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

        public function reviews()
    {
        return $this->hasMany(Review::class);
    }

      public function getIsActiveAttribute($value)
    {
        // Default true jika column tidak ada
        return $value ?? true;
    }

    // Mutator untuk is_active
    public function setIsActiveAttribute($value)
    {
        // Simpan di attribute, meskipun column tidak ada
        $this->attributes['is_active'] = $value;
    }
}
