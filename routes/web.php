<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\OverlayController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDonasiController;
use App\Http\Controllers\Admin\AdminSettingController;

// ── Guest (belum login) ──────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/',          [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// ── User biasa (role: user) ──────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transaksi',  [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/profil',     [ProfilController::class, 'edit'])->name('profil.edit');
    Route::post('/profil',    [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/overlay',    [OverlayController::class, 'edit'])->name('overlay.edit');
    Route::post('/overlay',   [OverlayController::class, 'update'])->name('overlay.update');
    Route::post('/overlay/regenerate-token', [OverlayController::class, 'regenerateToken'])->name('overlay.regenerate_token');
    Route::get('/donate',        [DonateController::class, 'show'])->name('donate.show');
    Route::post('/donate',       [DonateController::class, 'send'])->name('donate.send');
    Route::get('/donate/return', [DonateController::class, 'return'])->name('donate.return');
    Route::post('/logout',    [AuthController::class, 'logout'])->name('logout');
});

// ── Admin (role: admin) — dilindungi middleware 'admin' ──
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                      [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users',                 [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}',          [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::delete('/users/{user}',       [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/donasi',                [AdminDonasiController::class, 'index'])->name('donasi.index');
    Route::get('/donasi/export',         [AdminDonasiController::class, 'export'])->name('donasi.export');
    Route::get('/settings',              [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings',             [AdminSettingController::class, 'update'])->name('settings.update');
});

// ── Duitku callback (tanpa CSRF) ────────────────────────
Route::post('/donate/callback', [DonateController::class, 'callback'])->name('donate.callback');

// ── OBS Overlay (tanpa login, pakai token) ───────────────
Route::get('/obs/{token}',      [OverlayController::class, 'display'])->name('overlay.display');
Route::get('/obs/{token}/poll', [OverlayController::class, 'poll'])->name('overlay.poll');
