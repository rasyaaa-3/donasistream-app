<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuService
{
    private string $merchantCode;
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->merchantCode = config('duitku.merchant_code');
        $this->apiKey       = config('duitku.api_key');
        $this->baseUrl      = config('duitku.env') === 'production'
            ? 'https://passport.duitku.com/webapi'
            : 'https://sandbox.duitku.com/webapi';
    }

    /**
     * Buat transaksi baru ke Duitku
     * Endpoint: /api/merchant/v2/inquiry
     */
    public function createTransaction(array $data): array
    {
        $merchantOrderId = $data['merchant_order_id'];
        $paymentAmount   = (int) $data['amount'];

        // Signature: MD5(merchantCode + merchantOrderId + paymentAmount + apiKey)
        $signature = md5($this->merchantCode . $merchantOrderId . $paymentAmount . $this->apiKey);

        $payload = [
            'merchantCode'     => $this->merchantCode,
            'paymentAmount'    => $paymentAmount,
            'paymentMethod'    => $data['payment_method'],
            'merchantOrderId'  => $merchantOrderId,
            'productDetails'   => $data['product_details'],
            'customerVaName'   => $data['customer_name'],
            'email'            => $data['email'] ?? 'donor@donasistream.com',
            'phoneNumber'      => $data['phone'] ?? '',
            'returnUrl'        => route('donate.return'),
            'callbackUrl'      => route('donate.callback'),
            'signature'        => $signature,
            'merchantUserInfo' => $data['customer_name'],
            'expiryPeriod'     => 60,
        ];

        Log::info('Duitku request', ['url' => "{$this->baseUrl}/api/merchant/v2/inquiry", 'payload' => $payload]);

        $response = Http::timeout(30)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("{$this->baseUrl}/api/merchant/v2/inquiry", $payload);

        Log::info('Duitku response', ['status' => $response->status(), 'body' => $response->body()]);

        if ($response->serverError()) {
            Log::error('Duitku createTransaction server error', ['body' => $response->body()]);
            throw new \Exception('Gagal terhubung ke payment gateway.');
        }

        $result = $response->json();

        if ($response->clientError()) {
            $message = $result['Message'] ?? $result['statusMessage'] ?? 'Transaksi gagal.';
            throw new \Exception($message);
        }

        if (!isset($result['statusCode']) || $result['statusCode'] !== '00') {
            throw new \Exception($result['statusMessage'] ?? 'Transaksi gagal dibuat.');
        }

        return $result;
    }

    /**
     * Verifikasi signature callback dari Duitku
     * Signature: MD5(merchantCode + amount + merchantOrderId + apiKey)
     */
    public function verifyCallbackSignature(string $merchantCode, string $amount, string $merchantOrderId, string $signature): bool
    {
        $expected = md5($merchantCode . $amount . $merchantOrderId . $this->apiKey);
        return hash_equals($expected, $signature);
    }

    /**
     * Daftar metode pembayaran (hanya e-wallet & QRIS)
     */
    public function getPaymentMethods(): array
    {
        return [
            ['code' => 'OV',  'name' => 'OVO',        'type' => 'E-Wallet'],
            ['code' => 'DA',  'name' => 'DANA',        'type' => 'E-Wallet'],
            ['code' => 'SA',  'name' => 'ShopeePay',   'type' => 'E-Wallet'],
            ['code' => 'LA',  'name' => 'LinkAja',     'type' => 'E-Wallet'],
            ['code' => 'SP',  'name' => 'QRIS',        'type' => 'QRIS'],
        ];
    }
}
