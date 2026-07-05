<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama', 'username', 'email', 'password',
        'bio', 'instagram', 'initials', 'overlay_token',
        'role', 'is_suspended',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_suspended'      => 'boolean',
    ];

    // Auto-generate initials & overlay_token
    protected static function booted(): void {
        static::creating(function (User $user) {
            if (empty($user->initials)) {
                $words = explode(' ', trim($user->nama));
                $initials = '';
                foreach (array_slice($words, 0, 2) as $word) {
                    $initials .= strtoupper(mb_substr($word, 0, 1));
                }
                $user->initials = $initials ?: 'U';
            }
            if (empty($user->overlay_token)) {
                $user->overlay_token = Str::random(40);
            }
        });
    }

    // ── Role helpers ──
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isUser(): bool  { return $this->role === 'user'; }

    // ── Relations ──
    public function donasi() { return $this->hasMany(Donasi::class, 'user_id'); }
    public function overlaySetting() { return $this->hasOne(OverlaySetting::class, 'user_id'); }

    // ── Accessors ──
    public function getDonateUrlAttribute(): string { return 'Qeqink.id/' . $this->username; }
}
