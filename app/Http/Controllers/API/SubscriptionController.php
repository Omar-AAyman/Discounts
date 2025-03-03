<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SendPushNotificationJob;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use App\Services\LahzaPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\File;
use Laravel\Sanctum\PersonalAccessToken;
use stdClass;
use Tymon\JWTAuth\Facades\JWTAuth;

class SubscriptionController extends Controller
{
    public function initiate(Request $request, LahzaPaymentService $paymentService)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'period_in_months' => 'required|in:1,3,6,12',
        ]);

        // Ensure user is authenticated before accessing their data
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        // Check for existing active subscription
        $currentSubscription = Subscription::where('user_id', $user->id)
            ->where('is_online', 1)
            ->latest()
            ->first();

        if ($currentSubscription) {
            $currentExpiresAt = $currentSubscription->created_at->copy()->addMonths($currentSubscription->period_in_months);

            // Mark subscription as expired if needed
            if (!$currentSubscription->is_online || $currentSubscription->expires_at < now() || $currentExpiresAt < now()) {
                $currentSubscription->update(['is_online' => false]);
            }

            // Prevent new subscription if current is still active and not a guest subscription
            if ($currentExpiresAt > now() && $currentSubscription->type !== 'guest_subscription') {
                return response()->json([
                    'status' => false,
                    'message' => 'You already have an active subscription.',
                    'payment_url' => null,
                    'current_subscription' => [
                        'package' => Package::find($currentSubscription->package_id)->name,
                        'expires_at' => $currentExpiresAt->toDateTimeString(),
                        'type' => $currentSubscription->type,
                    ]
                ], 200);
            }
        }

        // Proceed with payment initiation
        $package = Package::findOrFail($validated['package_id']);
        $getPackageCostVarName = strtolower($package->name) . '_cost_per_month';
        $package->amount = $package->$getPackageCostVarName;
        $package->period_in_months = $validated['period_in_months'];

        $userData = $user->toArray() + $validated;

        try {
            $paymentUrl = $paymentService->initiatePayment($package, $userData);
            return response()->json([
                'status' => true,
                'message' => 'You have no active subscriptions, so you are eligible to subscribe.',
                'payment_url' => $paymentUrl,
                'current_subscription' => new stdClass()
            ]);
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
                    'amount' => ($subscription->period_in_months ?? 1) * $package->amount,
                    'amount_after_discount' => ($subscription->period_in_months ?? 1) * $package->amount,
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
                    'status' => 'success',
                ];

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

    public function checkUserSubscription(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized or Token has expired, please login again!',
                'token_status' => false,
                'subscription' => new stdClass(),
            ]);
        }

        try {
            // Authenticate user (JWT or Sanctum)
            $accessToken = PersonalAccessToken::findToken($token);
            $user = $accessToken ? $accessToken->tokenable : null;

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized or Token has expired, please login again!',
                    'token_status' => false,
                    'subscription' => new stdClass(),
                ]);
            }

            // **Check if user is a client**
            if ($user->type === 'client') {
                // Fetch the latest active subscription for the user
                $subscription = Subscription::where('user_id', $user->id)
                    ->where('type', 'user_subscription')
                    ->latest()
                    ->first();

                if (!$subscription) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No subscription found',
                        'token_status' => true,
                        'subscription' => new stdClass(),
                    ]);
                }

                // Calculate expected expiration date
                $periodInMonths = $subscription->period_in_months ?? 0;
                $calculatedExpiresAt = $subscription->created_at->copy()->addMonths($periodInMonths);

                if (!$subscription->is_online || $subscription->expires_at < now() || $calculatedExpiresAt < now()) {
                    $subscription->update(['is_online' => false]);

                    return response()->json([
                        'status' => false,
                        'message' => 'Subscription is expired or inactive',
                        'token_status' => true,
                        'subscription' => [
                            'user_id' => $user->id,
                            'package' => $subscription->package->name ?? null,
                            'expires_at' => $subscription->expires_at,
                            'is_online' => false,
                            'created_at' => $subscription->created_at,
                            'type' => $subscription->type,
                            'period_in_months' => $periodInMonths,
                        ],
                    ]);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Subscription is active',
                    'token_status' => true,
                    'subscription' => [
                        'user_id' => $user->id,
                        'package' => $subscription->package->name ?? null,
                        'expires_at' => $subscription->expires_at,
                        'is_online' => true,
                        'created_at' => $subscription->created_at,
                        'type' => $subscription->type,
                        'period_in_months' => $periodInMonths,
                    ],
                ]);
            }

            // **For non-client users, only check if the token is valid**
            return response()->json([
                'status' => true,
                'message' => 'User is authenticated',
                'token_status' => true,
                'subscription' => new stdClass(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired token',
                'token_status' => false,
                'subscription' => new stdClass(),
            ]);
        }
    }
}
