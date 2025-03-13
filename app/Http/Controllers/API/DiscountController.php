<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SendPushNotificationJob;
use App\Models\Invoice;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class DiscountController extends Controller
{
    /**
     * Handle a customer's request for a discount.
     */
    public function requestDiscount(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|exists:stores,id',
            'amount' => 'required|numeric|min:0.01',
            'amount_after_discount' => 'required|numeric|min:0|lt:amount',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'data' => $validator->errors()
            ], 200);
        }

        // Get the authenticated user
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
                'data' => null
            ], 200);
        }

        // Check if the store exists
        $store = Store::find($request->store_id);
        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found.',
                'data' => null
            ], 200);
        }

        // Get the seller (store owner)
        $seller = User::find($store->user_id);
        if (!$seller) {
            return response()->json([
                'success' => false,
                'message' => 'Seller not found.',
                'data' => null
            ], 200);
        }

        try {
            DB::beginTransaction();

            // Check if there's already a pending request from the user for the same store
            $existingInvoice = Invoice::where('user_id', $user->id)
                ->where('store_id', $request->store_id)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->first();

            if ($existingInvoice) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending discount request for this store.',
                    'data' => null
                ], 200);
            }

            // Create a new invoice
            $invoice = Invoice::create([
                'user_id' => $user->id,
                'store_id' => $request->store_id,
                'amount' => $request->amount,
                'amount_after_discount' => $request->amount_after_discount,
                'status' => 'pending',
                'type' => 'products',
            ]);

            // **Increment user points by 1**
            $user->increment('points');

            DB::commit();
            // Prepare notification details
            $notificationDetails = [
                'id' => $invoice->id ?? 1,
                'ar_title' => 'طلب جديد',
                'ar_description' => 'لقد تلقيت طلبًا جديدًا من العميل #' . $user->id,
                'en_title' => 'New Order Received',
                'en_description' => 'You have received a new order from customer #' . $user->id,
            ];

            // Dispatch push notification to the seller
            SendPushNotificationJob::dispatch($seller, $notificationDetails, $seller->operating_system);

            return response()->json([
                'success' => true,
                'message' => 'Discount request submitted successfully. Seller has been notified.',
                'data' => $invoice
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, please try again.',
                'data' => null
            ], 500);
        }
    }




    public function getUserDiscountRequests(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
                'data' => null
            ], 200);
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

        // Fetch invoices and eager-load related store and user
        $query = Invoice::where('user_id', $user->id)
            ->where('type', 'products')
            ->orderBy('created_at', 'desc')
            ->with(['store', 'user']); // Eager load store and user

        if ($dateLimit) {
            $query->where('created_at', '>=', $dateLimit);
        }

        $invoices = $query->get();

        // Transform each invoice to include store & user details
        $invoices->transform(function ($invoice) {
            return [
                'id' => $invoice->id,
                'amount' => $invoice->amount,
                'amount_after_discount' => $invoice->amount_after_discount,
                'status' => $invoice->status,
                'created_at' => $invoice->created_at,
                'store' => $invoice->store ? [
                    'name' => $invoice->store->name,
                    'image' => $invoice->store->store_img
                ] : null,
            ];
        });
        // Separate pending and paid invoices
        $pending = $invoices->where('status', 'pending')->sortByDesc('created_at')->values();
        $paid = $invoices->where('status', 'paid')->sortByDesc('created_at')->values();

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
            'message' => 'User discount requests retrieved successfully.',
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
}
