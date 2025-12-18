<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu apakah kolom sudah ada
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('email');
            });
        }

        if (!Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('address')->nullable()->after('phone');
            });
        }

        if (!Schema::hasColumn('users', 'city')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('city')->nullable()->after('address');
            });
        }

        if (!Schema::hasColumn('users', 'postal_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('postal_code', 10)->nullable()->after('city');
            });
        }

        if (!Schema::hasColumn('users', 'birthdate')) {
            Schema::table('users', function (Blueprint $table) {
                $table->date('birthdate')->nullable()->after('postal_code');
            });
        }
    }

    public function down(): void
    {
        // Hanya drop jika kolom ada
        Schema::table('users', function (Blueprint $table) {
            $columns = ['phone', 'address', 'city', 'postal_code', 'birthdate'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
