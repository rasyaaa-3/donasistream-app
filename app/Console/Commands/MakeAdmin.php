<?php
namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command {
    protected $signature   = 'admin:set {email}';
    protected $description = 'Jadikan user sebagai superadmin';

    public function handle() {
        $user = User::where('email', $this->argument('email'))->first();
        if (!$user) { $this->error("User tidak ditemukan."); return 1; }
        $user->update(['is_admin' => true]);
        $this->info("✓ {$user->nama} ({$user->email}) sekarang adalah admin.");
        return 0;
    }
}
