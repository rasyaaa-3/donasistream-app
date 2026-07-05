<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom role: 'admin' atau 'user'
            $table->enum('role', ['admin', 'user'])->default('user')->after('initials');
        });

        // Migrasi data lama: kalau is_admin=true → role='admin'
        if (Schema::hasColumn('users', 'is_admin')) {
            DB::statement("UPDATE users SET role = 'admin' WHERE is_admin = 1");
        }
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
