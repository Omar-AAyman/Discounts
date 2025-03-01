<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SendPushNotificationJob;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\User;
use App\Services\LahzaPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function initiate(Request $request, LahzaPaymentService $paymentService)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'period_in_months' => 'required|in:1,3,6,12',
        ]);

        $package = Package::findOrFail($validated['package_id']);
        $getPackageCostVarName = strtolower($package->name) . '_cost_per_month';
        $package->amount = $package->$getPackageCostVarName; // Access the dynamic attribute
        $package->period_in_months = $validated['period_in_months']; // Access the dynamic attribute

        // Ensure user is authenticated before accessing their data
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userData = auth()->user()->toArray() + $validated;

        try {
            $paymentUrl = $paymentService->initiatePayment($package, $userData);
            return response()->json(['payment_url' => $paymentUrl]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request, LahzaPaymentService $paymentService)
    {
        Log::info('Payment callback received', $request->all());

        $validated = $request->validate([
            'reference' => 'required|string|exists:subscriptions,transaction_id',
        ]);

        try {
            Log::info('Handling payment callback', ['data' => $validated]);

            $subscription = $paymentService->handleCallback($validated);
            Log::info('Subscription callback handled', ['subscription' => $subscription]);

            if ($subscription['status'] === 'paid') {
                Log::info('Payment successful, processing subscription', ['user_id' => $subscription['user_id']]);

                $user = User::find($subscription['user_id']);
                if (!$user) {
                    Log::error('User not found', ['user_id' => $subscription['user_id']]);
                    return redirect()->route('payments-summary', ['status' => 'error']);
                }

                $package = Package::findOrFail($subscription['package_id']);
                $getPackageCostVarName = strtolower($package->name) . '_cost_per_month';
                $package->amount = $package->$getPackageCostVarName; // Access the dynamic attribute

                // Create a new invoice
                $invoice = Invoice::create([
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->id,
                    'amount' => $subscription-> period_in_months ?? 1 * $package->amount,
                    'status' => 'paid',
                    'type' => 'subscription',
                ]);
                Log::info('Invoice created successfully', ['invoice_id' => $invoice->id ?? 'N/A']);

                // Prepare subscription notification details for the user
                $notificationDetails = [
                    'id' => $invoice->id ?? 1,
                    'ar_title' => 'تم تفعيل اشتراكك',
                    'ar_description' => 'تهانينا! لقد تم تفعيل اشتراكك بنجاح. استمتع بالمزايا الحصرية الآن.',
                    'en_title' => 'Your Subscription is Active!',
                    'en_description' => 'Congratulations! Your subscription has been successfully activated. Enjoy your exclusive benefits now.',
                    'type' => 'subscription',
                ];

                // Dispatch push notification to the user
                SendPushNotificationJob::dispatch($user, $notificationDetails, $user->operating_system);
                // Attempt to send the push notification separately
                try {
                    Log::info('Dispatching push notification', ['user_id' => $user->id]);
                    SendPushNotificationJob::dispatch($user, $notificationDetails, $user->operating_system);
                    Log::info('Push notification dispatched successfully');
                } catch (\Exception $e) {
                    Log::error('Failed to send push notification', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }

                return redirect()->route('payments-summary', ['status' =>  'success']);

            } else {
                Log::warning('Payment not completed', ['subscription' => $subscription]);

                return redirect()->route('payments-summary', ['status' =>  'fail']);
            }
        } catch (\Exception $e) {
            Log::error('Callback handling failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => request()->all(),
            ]);
            return redirect()->route('payments-summary', ['status' => 'fail']);
        }
    }
}
