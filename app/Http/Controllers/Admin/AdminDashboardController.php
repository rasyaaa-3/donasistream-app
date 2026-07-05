<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Donasi;

class AdminDashboardController extends Controller {
    public function index() {
        $totalUser    = User::where('role', 'user')->count();
        $totalDonasi  = Donasi::where('payment_status', 'paid')->count();
        $totalNominal = Donasi::where('payment_status', 'paid')->sum('jumlah');
        $todayNominal = Donasi::where('payment_status', 'paid')->whereDate('paid_at', today())->sum('jumlah');
        $todayDonasi  = Donasi::where('payment_status', 'paid')->whereDate('paid_at', today())->count();

        // Grafik 7 hari terakhir
        $chart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chart[] = [
                'label'   => $date->format('d M'),
                'nominal' => Donasi::where('payment_status','paid')->whereDate('paid_at', $date)->sum('jumlah'),
                'count'   => Donasi::where('payment_status','paid')->whereDate('paid_at', $date)->count(),
            ];
        }

        $recentDonasi = Donasi::with('user')->where('payment_status','paid')->orderByDesc('paid_at')->limit(10)->get();
        $recentUsers  = User::where('role', 'user')->orderByDesc('created_at')->limit(5)->get();

        return view('admin.dashboard', compact('totalUser','totalDonasi','totalNominal','todayNominal','todayDonasi','chart','recentDonasi','recentUsers'));
    }
}
