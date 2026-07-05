<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hampir semua query di DashboardController, TransaksiController,
 * OverlayController::poll, dan AdminDonasiController memfilter tabel
 * `donasi` dengan kombinasi user_id + payment_status, lalu mengurutkan
 * berdasarkan created_at/paid_at. Tanpa index komposit, MySQL akan full
 * table scan begitu jumlah donasi tumbuh besar (ratusan ribu baris),
 * dan setiap halaman dashboard/overlay polling akan makin lambat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            $table->index(['user_id', 'payment_status', 'created_at'], 'donasi_user_status_created_idx');
            $table->index(['payment_status', 'paid_at'], 'donasi_status_paidat_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['role', 'is_suspended'], 'users_role_suspended_idx');
        });
    }

    public function down(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            $table->dropIndex('donasi_user_status_created_idx');
            $table->dropIndex('donasi_status_paidat_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_suspended_idx');
        });
    }
};
