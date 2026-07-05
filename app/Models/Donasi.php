<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasi';

    protected $fillable = [
        'user_id',
        'donor_nama',
        'donor_initial',
        'donor_color',
        'jumlah',
        'pesan',
        'payment_status',
        'merchant_order_id',
        'payment_method',
        'payment_url',
        'va_number',
        'paid_at',
    ];

    protected $casts = [
        'jumlah'     => 'float',
        'created_at' => 'datetime',
        'paid_at'    => 'datetime',
    ];

    // ── Scopes ──
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // ── Relations ──
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Accessors ──
    public function getJumlahFormatAttribute(): string
    {
        $amount = $this->jumlah;
        if ($amount >= 1_000_000) return 'Rp ' . number_format($amount / 1_000_000, 1) . ' Jt';
        if ($amount >= 1_000)    return 'Rp ' . number_format($amount / 1_000, 0, ',', '.') . ' Rb';
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public function getWaktuAttribute(): string
    {
        $diff = now()->diff($this->created_at);
        if ($diff->days > 0)  return $this->created_at->format('d M, H:i');
        if ($diff->h > 0)     return $diff->h . ' jam lalu';
        if ($diff->i > 0)     return $diff->i . ' menit lalu';
        return 'Baru saja';
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->payment_status === 'paid';
    }
}
