<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\User;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('overlay_token', 64)->nullable()->unique()->after('initials');
        });
        User::whereNull('overlay_token')->each(function ($user) {
            $user->overlay_token = Str::random(40);
            $user->save();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('overlay_token');
        });
    }
};
