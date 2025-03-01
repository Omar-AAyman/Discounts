<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\LahzaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected $lahzaService;

    // Initialize a new transaction
    public function __construct(LahzaService $lahzaService)
    {
        $this->lahzaService = $lahzaService;
    }

    // Initialize a new transaction and store in DB
    public function initialize(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:ILS,JOD,USD',
            'email' => 'required|email',
            'mobile' => 'sometimes|string',
        ]);

        $validated['amount'] = $this->convertToMinorUnit($validated['amount'], $validated['currency']);
        $validated['callback_url'] = config('app.url') . '/webhook/lahza';
        $validated['reference'] = uniqid('txn_');

        DB::beginTransaction();
        try {
            // Send request to Lahza
            $response = $this->lahzaService->initializeTransaction($validated);

            if (!isset($response['success']) || !$response['success']) {
                return response()->json(['error' => $response['message']], 400);
            }

            // Store transaction in database
            $transaction = Transaction::create([
                'subscription_id' => $validated['subscription_id'],
                'reference' => $validated['reference'],
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'status' => 'pending',
                'email' => $validated['email'],
            ]);

            DB::commit();
            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction Initialization Failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }

    // Verify transaction and update status
    public function verify($reference)
    {
        $response = $this->lahzaService->verifyTransaction($reference);

        if (!isset($response['success']) || !$response['success']) {
            return response()->json(['error' => 'Transaction verification failed'], 400);
        }

        $transaction = Transaction::where('reference', $reference)->first();
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $transaction->update(['status' => $response['status']]);

        return response()->json(['message' => 'Transaction updated', 'transaction' => $transaction]);
    }

    public function handleWebhook(Request $request)
    {
        $data = $request->all();
        $reference = $data['reference'] ?? null;
        $status = $data['status'] ?? null;

        if (!$reference || !$status) {
            return response()->json(['error' => 'Invalid webhook data'], 400);
        }

        $transaction = Transaction::where('reference', $reference)->first();
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $transaction->update(['status' => $status]);

        return response()->json(['message' => 'Webhook processed']);
    }
    
    // Convert amount to minor units
    private function convertToMinorUnit(float $amount, string $currency): int
    {
        return match ($currency) {
            'ILS' => (int) round($amount * 100),
            'JOD' => (int) round($amount * 1000),
            'USD' => (int) round($amount * 100),
            default => throw new \InvalidArgumentException('Invalid currency'),
        };
    }
}
