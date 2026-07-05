<?php

namespace App\Support;

class ReservedUsername
{
    /**
     * Username yang tidak boleh dipakai karena:
     * - bertabrakan dengan segment route yang sudah ada (mis. /donate/return, /donate/callback)
     * - berpotensi disalahgunakan untuk menyamar sebagai halaman resmi (admin, support, dst.)
     *
     * Daftar ini sengaja dipisah dari controller supaya bisa dipakai ulang
     * di form registrasi maupun form update profil, dan mudah ditambah di masa depan
     * (mis. kalau nanti dibuat alias /@username).
     */
    public static function list(): array
    {
        return [
            'admin', 'administrator', 'root', 'superadmin',
            'login', 'logout', 'register', 'password',
            'dashboard', 'profil', 'profile', 'overlay', 'obs',
            'donate', 'donasi', 'transaksi', 'settings', 'setting',
            'return', 'callback', 'send', 'api', 'about', 'help',
            'support', 'contact', 'null', 'undefined', 'user', 'users',
        ];
    }

    public static function isReserved(?string $username): bool
    {
        if (!$username) {
            return false;
        }

        return in_array(strtolower($username), self::list(), true);
    }
}
