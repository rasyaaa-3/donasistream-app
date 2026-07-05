<?php

namespace App\Helpers;

class Rupiah
{
    /**
     * Format angka ke format Rupiah singkat (Jt / Rb).
     */
    public static function format(float $amount): string
    {
        if ($amount >= 1_000_000) {
            return 'Rp ' . number_format($amount / 1_000_000, 1, ',', '.') . ' Jt';
        }
        if ($amount >= 1_000) {
            return 'Rp ' . number_format($amount / 1_000, 0, ',', '.') . ' Rb';
        }
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
