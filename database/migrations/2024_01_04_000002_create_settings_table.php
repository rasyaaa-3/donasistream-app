<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Default settings
        DB::table('settings')->insert([
            ['key' => 'duitku_merchant_code', 'value' => env('DUITKU_MERCHANT_CODE', ''), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'duitku_api_key',       'value' => env('DUITKU_API_KEY', ''),       'created_at' => now(), 'updated_at' => now()],
            ['key' => 'duitku_env',            'value' => env('DUITKU_ENV', 'sandbox'),    'created_at' => now(), 'updated_at' => now()],
            ['key' => 'platform_fee_pct',      'value' => '0',                             'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maintenance_mode',      'value' => '0',                             'created_at' => now(), 'updated_at' => now()],
            ['key' => 'announcement',          'value' => '',                              'created_at' => now(), 'updated_at' => now()],
            ['key' => 'payment_methods',       'value' => json_encode(['OV','DA','SA','SP']), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'min_donate_ov',         'value' => '10000',                         'created_at' => now(), 'updated_at' => now()],
            ['key' => 'min_donate_da',         'value' => '10000',                         'created_at' => now(), 'updated_at' => now()],
            ['key' => 'min_donate_sa',         'value' => '10000',                         'created_at' => now(), 'updated_at' => now()],
            ['key' => 'min_donate_sp',         'value' => '10000',                         'created_at' => now(), 'updated_at' => now()],
        ]);
    }
    public function down(): void {
        Schema::dropIfExists('settings');
    }
};
