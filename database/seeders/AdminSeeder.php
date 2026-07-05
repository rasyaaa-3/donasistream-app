<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder {
    public function run(): void {
        // Cek dulu, jangan buat duplikat
        if (User::where('email', 'admin@gmail.com')->exists()) {
            $this->command->info('Admin sudah ada, skip.');
            return;
        }

        User::create([
            'nama'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'initials' => 'AD',
        ]);

        $this->command->info('✓ Akun admin berhasil dibuat: admin@gmail.com / admin123');
    }
}
