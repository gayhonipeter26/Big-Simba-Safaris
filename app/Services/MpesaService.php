<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class MpesaService
{
    private ?string $consumerKey;

    private ?string $consumerSecret;

    private ?string $shortcode;

    private ?string $tillNumber;

    private string $transactionType;

    private ?string $passkey;

    private string $baseUrl;

    private string $callbackUrl;

    public function __construct()
    {
        $this->consumerKey = config('services.mpesa.key');
        $this->consumerSecret = config('services.mpesa.secret');
        $this->shortcode = config('services.mpesa.shortcode');
        $this->tillNumber = config('services.mpesa.till_number');
        $this->transactionType = config('services.mpesa.transaction_type') ?? 'CustomerPayBillOnline';
        $this->passkey = config('services.mpesa.passkey');
        $this->baseUrl = config('services.mpesa.env') === 'sandbox'
            ? 'https://sandbox.safaricom.co.ke'
            : 'https://api.safaricom.co.ke';
        $this->callbackUrl = config('services.mpesa.callback_url')
            ?: config('app.url').'/api/mpesa/callback';
    }

    /**
     * Get OAuth token from Daraja
     */
    public function getAccessToken(): ?string
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get($this->baseUrl.'/oauth/v1/generate?grant_type=client_credentials');

        if ($response->successful()) {
            $data = $response->json();

            return $data['access_token'] ?? null;
        }

        return null;
    }

    /**
     * Initiate STK Push (Lipa Na M-Pesa Online)
     */
    public function stkPush(string $phoneNumber, float $amount, string $reference, string $description = 'Payment for Safari Services'): array
    {
        if (empty($this->callbackUrl)) {
            return ['status' => 'error', 'message' => 'M-Pesa callback URL is not configured'];
        }

        $token = $this->getAccessToken();
        if (! $token) {
            return ['status' => 'error', 'message' => 'Failed to generate access token'];
        }

        $timestamp = Carbon::now()->format('YmdHis');
        $password = base64_encode($this->shortcode.$this->passkey.$timestamp);

        // Format phone to 254...
        $phoneNumber = $this->formatPhone($phoneNumber);

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => $this->transactionType,
            'Amount' => round($amount),
            'PartyA' => $phoneNumber,
            'PartyB' => $this->transactionType === 'CustomerBuyGoodsOnline' ? ($this->tillNumber ?? $this->shortcode) : $this->shortcode,
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => $reference,
            'TransactionDesc' => $description,
        ];

        $response = Http::withToken($token)->post($this->baseUrl.'/mpesa/stkpush/v1/processrequest', $payload);

        \Log::info('Daraja API Response (STK Push): ', $response->json() ?? ['error' => 'No JSON returned']);

        return $response->json() ?? [];
    }

    /**
     * Register C2B URLs mission protocol
     */
    public function registerUrls(string $shortCode, string $validationUrl, string $confirmationUrl): array
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return ['status' => 'error', 'message' => 'Failed to generate access token'];
        }

        $payload = [
            'ShortCode' => $shortCode,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => $confirmationUrl,
            'ValidationURL' => $validationUrl,
        ];

        $response = Http::withToken($token)->post($this->baseUrl.'/mpesa/c2b/v1/registerurl', $payload);

        return $response->json();
    }

    private function formatPhone(string $phone): string
    {
        $phone = str_replace(['+', ' '], '', $phone);
        if (str_starts_with($phone, '0')) {
            return '254'.substr($phone, 1);
        }
        if (str_starts_with($phone, '7') || str_starts_with($phone, '1')) {
            return '254'.$phone;
        }

        return $phone;
    }
}
