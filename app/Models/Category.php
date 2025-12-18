<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'image',
        'is_active', 'parent_id'
    ];

    // Relasi: Satu kategori punya banyak produk
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Relasi: Kategori punya parent (untuk sub-kategori)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relasi: Kategori punya children
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
