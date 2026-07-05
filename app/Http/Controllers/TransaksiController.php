<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donasi;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();
        $userId = $user->id;

        $filter = $request->get('filter', 'semua');
        $search = trim($request->get('q', ''));

        $query = Donasi::where('user_id', $userId);

        // Filter by period
        match ($filter) {
            'hari_ini'  => $query->whereDate('created_at', today()),
            'bulan_ini' => $query->whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year),
            default     => null,
        };

        // Search by donor name
        if ($search) {
            $query->where('donor_nama', 'like', "%{$search}%");
        }

        $transaksi = $query->orderByDesc('created_at')->get();

        // Summary stats (always from full dataset)
        $totalMasuk  = Donasi::where('user_id', $userId)->sum('jumlah');
        $totalOrang  = Donasi::where('user_id', $userId)->count();
        $tertinggi   = Donasi::where('user_id', $userId)->max('jumlah') ?? 0;
        $rataRata    = $totalOrang > 0 ? ($totalMasuk / $totalOrang) : 0;

        return view('transaksi.index', compact(
            'user',
            'transaksi',
            'filter',
            'search',
            'totalMasuk',
            'totalOrang',
            'tertinggi',
            'rataRata'
        ));
    }
}
