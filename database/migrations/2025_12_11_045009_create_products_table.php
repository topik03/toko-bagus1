<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_products_table.php
public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->decimal('price', 12, 2); // harga normal
        $table->decimal('discount_price', 12, 2)->nullable(); // harga diskon
        $table->integer('stock')->default(0);
        $table->integer('weight')->default(1000); // dalam gram
        $table->string('unit')->default('pcs'); // kg, liter, bungkus, etc
        $table->string('sku')->nullable()->unique(); // kode produk
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('main_image')->nullable();
        $table->boolean('is_active')->default(true);
        $table->boolean('is_featured')->default(false);
        $table->boolean('is_best_seller')->default(false);
        $table->integer('views')->default(0);
        $table->integer('sold_count')->default(0);
        $table->timestamps();
        $table->softDeletes(); // untuk archive
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
