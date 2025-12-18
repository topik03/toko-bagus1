<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // TAMBAH 2 COLUMN SEKALIGUS
            $table->string('tracking_number')->nullable()->after('order_status');
            $table->string('shipping_carrier')->nullable()->after('tracking_number');
            // Kalau mau tambah lebih banyak:
            // $table->string('shipping_service')->nullable()->after('shipping_carrier');
            // $table->date('estimated_delivery')->nullable()->after('shipping_carrier');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // HAPUS 2 COLUMN
            $table->dropColumn(['tracking_number', 'shipping_carrier']);
        });
    }
};
