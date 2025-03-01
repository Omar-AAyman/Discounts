<?php

namespace App\Http\Controllers\API\Sellers;

use App\Http\Controllers\Controller;
use App\Jobs\SendPushNotificationJob;
use App\Models\Invoice;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;



class DiscountController extends Controller
{
    public function getDiscountRequests(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
                'data' => null
            ], 401);
        }

        // Get the store associated with the seller
        $store = Store::where('user_id', $user->id)->first();
        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found.',
                'data' => null
            ], 404);
        }

        // Determine the date filter
        $filter = $request->query('filter'); // Get the filter value from query params
        $dateLimit = null;

        if ($filter === 'current_month') {
            $dateLimit = now()->startOfMonth(); // Start of the current month
        } elseif ($filter === 'six_months') {
            $dateLimit = now()->subMonths(6)->startOfMonth(); // Start of 6 months ago
        } elseif ($filter === 'twelve_months') {
            $dateLimit = now()->subMonths(12)->startOfMonth(); // Start of 12 months ago
        }

        // Fetch invoices based on the filter
        $query = Invoice::where('store_id', $store->id)
            ->where('type', 'products')
            ->orderBy('created_at', 'desc');

        if ($dateLimit) {
            $query->where('created_at', '>=', $dateLimit);
        }

        $invoices = $query->with('user')->get();

        // Add store & client details inside each invoice
        $invoices->transform(function ($invoice) use ($store) {
            return [
                'id' => $invoice->id,
                'amount' => $invoice->amount,
                'amount_after_discount' => $invoice->amount_after_discount,
                'status' => $invoice->status,
                'created_at' => $invoice->created_at,
                'store' => [
                    'name' => $store->name,
                    'image' => $store->store_img
                ],
                'user' => $invoice->user ? [
                    'name' => $invoice->user->fullname ?? 'N/A',
                    'email' => $invoice->user->email ?? 'N/A',
                    'phone' => $invoice->user->phone ?? 'N/A',
                ] : null
            ];
        });

        // Separate pending and paid invoices
        $pending = $invoices->where('status', 'pending')->values();
        $paid = $invoices->where('status', 'paid')->values();

        // Calculate total amounts (Master Totals)
        $totalBeforeDiscount = $invoices->sum('amount');
        $totalAfterDiscount = $invoices->sum('amount_after_discount');
        $totalDiscountAmount = $totalBeforeDiscount - $totalAfterDiscount;

        // Calculate pending totals
        $pendingTotalBefore = $pending->sum('amount');
        $pendingTotalAfter = $pending->sum('amount_after_discount');
        $pendingTotalDiscount = $pendingTotalBefore - $pendingTotalAfter;

        // Calculate paid totals
        $paidTotalBefore = $paid->sum('amount');
        $paidTotalAfter = $paid->sum('amount_after_discount');
        $paidTotalDiscount = $paidTotalBefore - $paidTotalAfter;

        return response()->json([
            'success' => true,
            'message' => 'Discount requests retrieved successfully.',
            'data' => [
                'total_before_discount' => $totalBeforeDiscount,
                'total_after_discount' => $totalAfterDiscount,
                'total_discount_amount' => $totalDiscountAmount,
                'pending_requests' => [
                    'total_before_discount' => $pendingTotalBefore,
                    'total_after_discount' => $pendingTotalAfter,
                    'total_discount_amount' => $pendingTotalDiscount,
                    'requests' => $pending
                ],
                'paid_requests' => [
                    'total_before_discount' => $paidTotalBefore,
                    'total_after_discount' => $paidTotalAfter,
                    'total_discount_amount' => $paidTotalDiscount,
                    'requests' => $paid
                ]
            ]
        ], 200);
    }


    public function markAsPaid(Request $request, $invoiceId)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
                'data' => null
            ], 401);
        }

        // Get the store associated with the seller
        $store = Store::where('user_id', $user->id)->first();
        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found.',
                'data' => null
            ], 404);
        }

        // Find the invoice and ensure it belongs to the seller's store
        $invoice = Invoice::where('id', $invoiceId)
            ->where('store_id', $store->id)
            ->where('status', 'pending')
            ->first();

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found or already marked as paid.',
                'data' => null
            ], 404);
        }

        /// Retrieve the user (client) who placed the order
        $client = User::find($invoice->user_id); // Assuming `user_id` is stored in the invoice

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'data' => null
            ], 404);
        }

        // Mark the invoice as paid
        $invoice->update(['status' => 'paid']);

        // Prepare notification details
        $notificationDetails = [
            'id' => $invoice->id,
            'ar_title' => 'تم دفع الفاتورة',
            'ar_description' => 'تم دفع فاتورتك رقم #' . $invoice->id . ' بنجاح.',
            'en_title' => 'Invoice Paid',
            'en_description' => 'Your invoice #' . $invoice->id . ' has been successfully paid.',
        ];

        // Dispatch push notification job
        SendPushNotificationJob::dispatch($client, $notificationDetails, $client->operating_system);

        return response()->json([
            'success' => true,
            'message' => 'Invoice marked as paid successfully and notification sent.',
            'data' => $invoice
        ], 200);
    }
}
