<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            // Status pembayaran: pending | paid | failed | expired
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])
                  ->default('paid')
                  ->after('pesan');

            // Data Duitku
            $table->string('merchant_order_id', 50)->nullable()->unique()->after('payment_status');
            $table->string('payment_method', 10)->nullable()->after('merchant_order_id');
            $table->string('payment_url', 500)->nullable()->after('payment_method');
            $table->string('va_number', 50)->nullable()->after('payment_url');
            $table->timestamp('paid_at')->nullable()->after('va_number');
        });

        // Donasi lama (sebelum Duitku) dianggap sudah paid
        DB::table('donasi')->whereNull('merchant_order_id')->update(['payment_status' => 'paid']);
    }

    public function down(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'merchant_order_id', 'payment_method', 'payment_url', 'va_number', 'paid_at']);
        });
    }
};
