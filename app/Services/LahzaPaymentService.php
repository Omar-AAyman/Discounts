<?php

// app/Services/PaymentService.php
namespace App\Services;

use App\Jobs\SendPushNotificationJob;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LahzaPaymentService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.lahza.api_key');
        $this->baseUrl = config('services.lahza.base_url');
    }

    public function initiatePayment(Package $package, array $userData)
    {
        Log::info('Starting payment initiation', [
            'user_id' => $userData['id'],
            'package_id' => $package->id,
            'amount' => $package->amount,
            'currency' => 'ILS'
        ]);

        // Create pending subscription
        $subscription = Subscription::create([
            'user_id' => $userData['id'],
            'package_id' => $package->id,
            'status' => 'pending',
            'is_online' => false,
            'type' => 'user_subscription',
            'period_in_months' => $package->period_in_months,
            'expires_at' => now()->addMinutes(5)
        ]);

        Log::info('Subscription created', ['subscription_id' => $subscription->id]);

        // Request payment link from Lahza
        $payload = [
            'amount' => $package->amount * $package->period_in_months * 100,
            'currency' => 'ILS', // Example currency
            'reference_id' => $subscription->id,
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'email' => $userData['email'],
            'mobile' => $userData['phone'],
            'callback_url' => route('payment.callback'),
        ];

        Log::info('Sending payment request to Lahza', ['payload' => $payload]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'content-type' => 'application/json'
        ])->post("{$this->baseUrl}transaction/initialize", $payload);

        if ($response->failed()) {
            Log::error('Payment initiation failed', ['response' => $response->body()]);
            throw new \Exception('Payment initiation failed');
        }

        $responseData = $response->json();
        Log::info('Received response from Lahza', ['response' => $responseData]);

        $subscription->update(['transaction_id' => $responseData['data']['reference']]);

        return $responseData['data']['authorization_url'];
    }

    public function handleCallback(array $data)
    {
        Log::info('Received payment callback', ['data' => $data]);
        $paymentVerified = $this->verifyPayment($data['reference']);
        if (!$paymentVerified) {
            Log::error('Payment verification failed', ['reference' => $data['reference']]);
            abort(403, 'Payment verification failed');
        }

        $subscription = Subscription::where('transaction_id', $data['reference'])->first();

        if (!$subscription) {
            Log::error('Subscription not found', ['reference' => $data['reference']]);
            abort(404, 'Subscription not found');
        }
        Log::info('paymentVerified  ', ['data' => $paymentVerified]);

        $status = $paymentVerified ? 'paid' : 'failed';

        $updateData = [
            'status' => $status,
            'expires_at' => $status === 'paid'
                ? now()->addMonths($subscription->period_in_months)
                : $subscription->expires_at,
            'is_online' => true,

        ];

        $subscription->update($updateData);

        return $subscription;
    }

    public function verifyPayment($reference)
    {
        Log::info('Verifying payment', ['reference' => $reference]);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'content-type' => 'application/json'
        ])->get("{$this->baseUrl}transaction/verify/{$reference}");

        $responseData = $response->json();

        if ($responseData['data']['status'] != 'success') {
            Log::error('Verification failed', ['response' => $response->body()]);
            return false;
        }
        log::info("payment Succeed", ['status' => $responseData['data']['status']]);

        return $responseData['data']['status'] === 'success';
    }
}
