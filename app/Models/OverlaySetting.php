<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OverlaySetting extends Model
{
    protected $table = 'overlay_settings';

    protected $fillable = [
        'user_id',
        'posisi',
        'warna',
        'durasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
