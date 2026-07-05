<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Donasi;
use App\Helpers\Rupiah;
use App\Services\DuitkuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DonateController extends Controller
{
    public function __construct(private DuitkuService $duitku) {}

    /**
     * Halaman form donasi (login required — ini halaman simulasi untuk streamer sendiri)
     */
    public function show()
    {
        $user    = Auth::user();
        $riwayat = Donasi::where('user_id', $user->id)
                        ->paid()
                        ->orderByDesc('created_at')
                        ->limit(10)
                        ->get();

        $paymentMethods = $this->duitku->getPaymentMethods();

        return view('donate.show', compact('user', 'riwayat', 'paymentMethods'));
    }

    /**
     * Proses form donasi → buat transaksi ke Duitku → redirect ke halaman bayar
     */
    public function send(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'donor_nama'     => 'required|string|max:60',
            'jumlah'         => 'required|numeric|min:10000|max:10000000',
            'pesan'          => 'nullable|string|max:300',
            'payment_method' => 'required|string|max:5',
        ], [
            'donor_nama.required'     => 'Nama donatur wajib diisi.',
            'jumlah.required'         => 'Nominal donasi wajib diisi.',
            'jumlah.min'              => 'Minimal donasi Rp 10.000 (syarat Duitku).',
            'jumlah.max'              => 'Maksimal donasi Rp 10.000.000.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ]);

        // Generate initials & color
        $words    = explode(' ', trim($request->donor_nama));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(mb_substr($word, 0, 1));
        }
        $colors = ['#4DC8F0','#4ade80','#f0d54d','#f0914d','#a855f7','#ec4899'];
        $color  = $colors[abs(crc32($request->donor_nama)) % count($colors)];

        $merchantOrderId = 'DS-' . strtoupper(Str::random(12)) . '-' . time();

        // Simpan donasi dengan status pending
        $donasi = Donasi::create([
            'user_id'          => $user->id,
            'donor_nama'       => $request->donor_nama,
            'donor_initial'    => $initials ?: 'A',
            'donor_color'      => $color,
            'jumlah'           => (int) $request->jumlah,
            'pesan'            => $request->pesan,
            'payment_status'   => 'pending',
            'merchant_order_id'=> $merchantOrderId,
            'payment_method'   => $request->payment_method,
        ]);

        try {
            $result = $this->duitku->createTransaction([
                'merchant_order_id' => $merchantOrderId,
                'amount'            => (int) $request->jumlah,
                'payment_method'    => $request->payment_method,
                'product_details'   => 'Donasi untuk ' . $user->nama,
                'customer_name'     => $request->donor_nama,
            ]);

            // Simpan URL & VA number dari Duitku
            $donasi->update([
                'payment_url' => $result['paymentUrl'] ?? null,
                'va_number'   => $result['vaNumber'] ?? null,
            ]);

            // Redirect ke halaman pembayaran Duitku
            return redirect($result['paymentUrl']);

        } catch (\Exception $e) {
            $donasi->update(['payment_status' => 'failed']);
            Log::error('Duitku error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Callback dari Duitku (POST, dipanggil otomatis setelah donor bayar)
     * PENTING: route ini harus dikecualikan dari CSRF middleware
     */
    public function callback(Request $request)
    {
        $merchantCode    = $request->input('merchantCode');
        $amount          = $request->input('amount');
        $merchantOrderId = $request->input('merchantOrderId');
        $resultCode      = $request->input('resultCode');
        $signature       = $request->input('signature');

        Log::info('Duitku callback', $request->all());

        // Verifikasi signature
        if (!$this->duitku->verifyCallbackSignature($merchantCode, $amount, $merchantOrderId, $signature)) {
            Log::warning('Duitku callback: signature tidak valid', $request->all());
            return response('Invalid signature', 400);
        }

        $donasi = Donasi::where('merchant_order_id', $merchantOrderId)->first();

        if (!$donasi) {
            Log::warning('Duitku callback: donasi tidak ditemukan', ['merchantOrderId' => $merchantOrderId]);
            return response('Order not found', 404);
        }

        if ($resultCode === '00') {
            // Pembayaran berhasil
            $donasi->update([
                'payment_status' => 'paid',
                'paid_at'        => now(),
            ]);
        } else {
            $donasi->update(['payment_status' => 'failed']);
        }

        return response('OK', 200);
    }

    /**
     * Return URL — donor diarahkan ke sini setelah bayar di halaman Duitku
     */
    public function return(Request $request)
    {
        $merchantOrderId = $request->query('merchantOrderId');
        $resultCode      = $request->query('resultCode');

        $donasi = $merchantOrderId
            ? Donasi::where('merchant_order_id', $merchantOrderId)->first()
            : null;

        if ($resultCode === '00' || ($donasi && $donasi->payment_status === 'paid')) {
            return redirect()->route('donate.show')
                ->with('donate_success', true)
                ->with('donate_amount', $donasi ? Rupiah::format($donasi->jumlah) : '')
                ->with('donate_nama', $donasi->donor_nama ?? '');
        }

        return redirect()->route('donate.show')
            ->with('error', 'Pembayaran belum berhasil atau dibatalkan.');
    }
}
