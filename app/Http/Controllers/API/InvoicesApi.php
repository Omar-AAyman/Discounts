<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SendPushNotificationJob;
use App\Models\Invoice;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;


class InvoicesApi extends Controller
{


    // public function TestSendPushNotification()
    // {
    //     try {
    //         // Fake Account Holder Data
    //         $AccountHolder = (object) [
    //             'id' => 2,
    //             'name' => 'John Doe',
    //             'notification_lang' => 'en',
    //             'lang' => 'ar',
    //             'device_token' => 'f4t5cgzwRfCC-5HUFI-ade:APA91bFrGaas33Drqku0Up_eCDp_orhldXP5SFbb72tqmmoV8rTHQ74s5DGkFqmSagFr7dwVkUd7EbOnpM9zWhT-EavGUjzgwKKV_T39Mle17C_WrE4tfMk', // Use a real token for actual testing
    //             'operating_system' => 'android', // or 'ios'
    //         ];

    //         // Fake Notification Details
    //         $accountHolderNotificationDetails = [
    //             'id' => 1,
    //             'ar_title' => 'Test Notification',
    //             'ar_description' => 'This is a test push notification',
    //             'en_title' => 'Test Notification',
    //             'en_description' => 'This is a test push notification',
    //         ];

    //         // Fake Operating System
    //         $operating_system = $AccountHolder->operating_system;

    //         // Dispatch the Job
    //         dd(SendPushNotificationJob::dispatch($AccountHolder, $accountHolderNotificationDetails, $operating_system));

    //         // Return API Response
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Push notification test dispatched successfully.',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         Log::error('FCM Notification Error: ' . $e->getMessage());

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Failed to send push notification.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    public function clientPurchases(Request $request)
    {
        // Get the filter parameter from the request
        $filter = $request->input('filter', 'month'); // Default to 'month'

        // Determine the date range based on the filter
        // Determine the date range based on the filter
        if ($filter === '6months') {
            $dateRange = now()->subMonths(6);
        } elseif ($filter === '12months') {
            $dateRange = now()->subMonths(12);
        } else {
            $dateRange = now()->subMonth(); // Default to 1 month
        }

        if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {

            $user = $request->user();
            // Filter invoices based on the date range
            $invoices = Invoice::where('type', 'product')
                ->where('status', 'paid')
                ->where('created_at', '>=', $dateRange) // Filter by created_at date
                ->where('user_id', $user->id)
                ->with('product')
                ->get();
            $totalAfterDiscount = $invoices->sum('amount');

            $totalBeforeDiscount = 0;
            foreach ($invoices as $invoice) {
                if ($invoice->product->offer_id && $invoice->product->offer->is_online) {
                    for ($i = 0; $i < $invoice->quantity; $i++) {
                        $totalBeforeDiscount += $invoice->product->price;
                    }
                } else {
                    $totalBeforeDiscount += $invoice->amount;
                }
            }
            return response([
                'status' => true,
                'message' => 'Invoices retrieved successfully',
                'data' => [
                    'total price before discount' => $totalBeforeDiscount,
                    'total price after discount' => $totalAfterDiscount,
                    'discount' => $totalBeforeDiscount - $totalAfterDiscount,
                    'paid invoices' => $invoices,
                ],
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'Token is expired',
                'data' => [],
            ]);
        }
    }

    public function sellerSales(Request $request)
    {
        // Get the filter parameter from the request
        $filter = $request->input('filter', 'month'); // Default to 'month'
        $statusFilter = $request->input('status', 'pending'); // Default to 'pending'

        // Determine the date range based on the filter
        if ($filter === '6months') {
            $dateRange = now()->subMonths(6);
        } elseif ($filter === '12months') {
            $dateRange = now()->subMonths(12);
        } else {
            $dateRange = now()->subMonth(); // Default to 1 month
        }

        if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {

            $user = $request->user();
            // Get the seller's ID
            $sellerId = $user->id; // Assuming the user is the seller

            // Filter invoices based on the date range and seller's ID through product's store
            $invoices = Invoice::whereHas('product.store', function ($query) use ($sellerId) {
                $query->where('user_id', $sellerId);
            })
                ->where('invoices.status', $statusFilter) // Filter by invoice status
                ->where('invoices.created_at    ', '>=', $dateRange) // Filter by created_at date
                ->with(['product', 'user']) // Include user data
                ->get();

            $totalSales = $invoices->sum('amount');

            return response([
                'status' => true,
                'message' => 'Invoices retrieved successfully',
                'data' => [
                    'total sales' => $totalSales,
                    'invoices' => $invoices,
                ],
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'Token is expired',
                'data' => [],
            ]);
        }
    }

    public function getInvoiceDetails(Request $request, $invoiceId)
    {
        // Ensure the user is authenticated and the token is valid
        if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {
            $user = $request->user();
            $sellerId = $user->id; // Assuming the user is the seller

            // Retrieve the invoice details for the specific invoice ID and seller
            $invoice = Invoice::where('id', $invoiceId)
                ->whereHas('product.store', function ($query) use ($sellerId) {
                    $query->where('user_id', $sellerId);
                })
                ->with(['product', 'user']) // Include product and user data
                ->first();

            if ($invoice) {
                return response([
                    'status' => true,
                    'message' => 'Invoice details retrieved successfully',
                    'data' => $invoice,
                ]);
            } else {
                return response([
                    'status' => false,
                    'message' => 'Invoice not found or does not belong to the seller',
                    'data' => [],
                ]);
            }
        } else {
            return response([
                'status' => false,
                'message' => 'Token is expired',
                'data' => [],
            ]);
        }
    }

    public function markInvoiceAsPaid(Request $request, $invoiceId)
    {
        // Ensure the user is authenticated and the token is valid
        if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {
            $user = $request->user();
            $sellerId = $user->id; // Assuming the user is the seller

            // Find the invoice and ensure it belongs to the seller
            $invoice = Invoice::where('id', $invoiceId)
                ->whereHas('product.store', function ($query) use ($sellerId) {
                    $query->where('user_id', $sellerId);
                })
                ->first();

            if ($invoice) {
                // Check if the invoice is already marked as paid
                if ($invoice->status === 'paid') {
                    return response([
                        'status' => true,
                        'message' => 'Invoice has already been marked as paid',
                        'data' => $invoice,
                    ]);
                }

                // Mark the invoice as paid
                $invoice->status = 'paid';
                $invoice->save();

                return response([
                    'status' => true,
                    'message' => 'Invoice marked as paid successfully',
                    'data' => $invoice,
                ]);
            } else {
                return response([
                    'status' => false,
                    'message' => 'Invoice not found or does not belong to the seller',
                    'data' => [],
                ]);
            }
        } else {
            return response([
                'status' => false,
                'message' => 'Token is expired',
                'data' => [],
            ]);
        }
    }
}
