<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;

class AdminDonasiController extends Controller {
    public function index(Request $request) {
        $q = Donasi::with('user');
        if ($request->status) $q->where('payment_status', $request->status);
        if ($request->search) $q->where(function($q) use ($request){ $q->where('donor_nama','like',"%{$request->search}%")->orWhere('merchant_order_id','like',"%{$request->search}%"); });
        if ($request->date_from) $q->whereDate('created_at', '>=', $request->date_from);
        if ($request->date_to)   $q->whereDate('created_at', '<=', $request->date_to);
        $donasi = $q->orderByDesc('created_at')->paginate(25);
        $totalPaid    = Donasi::where('payment_status','paid')->sum('jumlah');
        $totalPending = Donasi::where('payment_status','pending')->count();
        return view('admin.donasi.index', compact('donasi','totalPaid','totalPending'));
    }

    public function export(Request $request) {
        $q = Donasi::with('user')->where('payment_status','paid');
        if ($request->date_from) $q->whereDate('paid_at', '>=', $request->date_from);
        if ($request->date_to)   $q->whereDate('paid_at', '<=', $request->date_to);
        $donasi = $q->orderByDesc('paid_at')->get();

        $csv = "ID,Streamer,Donor,Nominal,Metode,Pesan,Tanggal\n";
        foreach ($donasi as $d) {
            $csv .= "{$d->id},{$d->user->nama},{$d->donor_nama},{$d->jumlah},{$d->payment_method},\"{$d->pesan}\",{$d->paid_at}\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="donasi-' . now()->format('Ymd') . '.csv"',
        ]);
    }
}
