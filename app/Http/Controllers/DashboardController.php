<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donasi;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $userId = $user->id;

        // Stats
        $totalTerkumpul = Donasi::where('user_id', $userId)->sum('jumlah');
        $totalDonatur   = Donasi::where('user_id', $userId)->count();
        $bulanIni       = Donasi::where('user_id', $userId)
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->sum('jumlah');
        $terbesar       = Donasi::where('user_id', $userId)->max('jumlah') ?? 0;

        // Recent donations (5 latest)
        $recentDonations = Donasi::where('user_id', $userId)
                            ->orderByDesc('created_at')
                            ->limit(5)
                            ->get();

        // Top donors
        $topDonors = Donasi::where('user_id', $userId)
                        ->selectRaw('donor_nama, donor_color, SUM(jumlah) as total')
                        ->groupBy('donor_nama', 'donor_color')
                        ->orderByDesc('total')
                        ->limit(5)
                        ->get();

        $maxDonor = $topDonors->max('total') ?: 1;

        return view('dashboard.index', compact(
            'user',
            'totalTerkumpul',
            'totalDonatur',
            'bulanIni',
            'terbesar',
            'recentDonations',
            'topDonors',
            'maxDonor'
        ));
    }
}
