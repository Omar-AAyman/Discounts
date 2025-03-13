<?php

namespace App\Http\Controllers\API;

use App\Models\Invoice;
use App\Models\Option;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Laravel\Sanctum\PersonalAccessToken;


class UserSubscription extends Controller
{

    public function freeSubscription(Request $request)
{
    $token = PersonalAccessToken::findToken($request->bearerToken());

    if (!$token || $token->isExpired()) {
        return response([
            'status' => false,
            'message' => 'Token is expired'
        ], 200);
    }

    $user = $request->user();

    // Check if user has an active subscription
    if (Subscription::where('user_id', $user->id)->where('is_online', 1)->exists()) {
        return response([
            'status' => false,
            'message' => 'The user is currently subscribed'
        ], 200);
    }

    // Fetch package and free trial days in a single query
    $options = Option::whereIn('key', ['package_for_guest', 'free_trial_period_in_days'])
        ->pluck('value', 'key');

    $packageName = $options['package_for_guest'] ?? 'basic';
    $freeTrialDays = $options['free_trial_period_in_days'] ?? 7; // Default to 7 days if not set

    // Fetch package in one query
    $package = Package::where('name', $packageName)->first() ?? Package::where('name', 'basic')->first();

    if (!$package) {
        return response([
            'status' => false,
            'message' => 'No valid package found'
        ], 200);
    }

    // Ensure free subscription is only granted once
    if (Subscription::where('user_id', $user->id)->where('type', 'guest_subscription')->exists()) {
        return response([
            'status' => false,
            'message' => 'The user has been subscribed to the free plan before'
        ], 200);
    }

    // Create the free guest subscription
    Subscription::create([
        'user_id' => $user->id,
        'package_id' => $package->id,
        'type' => 'guest_subscription',
        'expires_at' => now()->addDays($freeTrialDays),
        'is_online' => 1,
    ]);

    return response([
        'status' => true,
        'message' => "You have been registered for the free plan for {$freeTrialDays} days"
    ], 200);
}

    public function getSuperPackageCostPerMonth()
    {
        $option = Option::where('key', 'super_cost_per_month')->first();
        if ($option) {
            $super_cost_per_month = floatval($option->value);
            return $super_cost_per_month;
        } else {
            return 59.99;
        }
    }


    public function getBasicPackageCostPerMonth()
    {
        $option = Option::where('key', 'basic_cost_per_month')->first();
        if ($option) {
            $basic_cost_per_month = floatval($option->value);
            return $basic_cost_per_month;
        } else {
            return 20.99;
        }
    }


    public function getElitePackageCostPerMonth()
    {
        $option = Option::where('key', 'elite_cost_per_month')->first();
        if ($option) {
            $elite_cost_per_month = floatval($option->value);
            return $elite_cost_per_month;
        } else {
            return 70.99;
        }
    }

    public function getAllPackages()
    {
        $packages = Package::where('is_online', 1)->with('sections')->select('id', 'name', 'description', 'is_online')->get();

        $packagesWithAmounts = $packages->map(function ($package) {

            switch ($package->name) {
                case 'super':
                    $package->amount = $this->getSuperPackageCostPerMonth();
                    break;
                case 'elite':
                    $package->amount = $this->getElitePackageCostPerMonth();
                    break;
                case 'basic':
                    $package->amount = $this->getBasicPackageCostPerMonth();
                    break;
            }
            return $package;
        });
        // dd($packagesWithAmounts);
        return response([
            'status' => true,
            'message' => 'Packages retrieved successfully',
            'packages' => $packagesWithAmounts
        ], 200);
    }
    public function packageUserSubscription(Request $request)
    {
        // Check if the token is expired
        if (PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {
            return response([
                'status' => false,
                'message' => 'Token is expired',
            ], 200);
        }

        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'period_in_months' => 'required|integer|min:1|max:12',
        ]);

        $user = $request->user();

        // Fetch the latest active subscription
        $currentSubscription = Subscription::where('user_id', $user->id)
            ->where('is_online', 1) // Ensure it's an active subscription
            ->latest()
            ->first();

        if ($currentSubscription) {
            // Calculate expiration date dynamically (created_at + period)
            $currentExpiresAt = $currentSubscription->created_at->addMonths($currentSubscription->period_in_months);

            // If subscription is still active and it's not guest_subscription, prevent new subscription
            if ($currentExpiresAt > now() && $currentSubscription->type !== 'guest_subscription') {
                return response([
                    'status' => false,
                    'message' => 'You already have an active subscription.',
                    'current_subscription' => [
                        'package' => Package::find($currentSubscription->package_id)->name,
                        'expires_at' => $currentExpiresAt->toDateTimeString(),
                        'type' => $currentSubscription->type,
                    ]
                ], 200);
            }

            // Mark old subscription as inactive (is_online = 0)
            $currentSubscription->update(['is_online' => 0]);
        }

        // Create the new subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'package_id' => $request->package_id,
            'period_in_months' => $request->period_in_months,
            'type' => 'user_subscription',
            'is_online' => 1, // Mark new subscription as active
        ]);

        // Calculate new expiration date
        $expiresAt = $subscription->created_at->addMonths($request->period_in_months);

        // Get package price
        $package = Package::findOrFail($request->package_id);
        $amount = match ($package->name) {
            'super' => $this->getSuperPackageCostPerMonth(),
            'elite' => $this->getElitePackageCostPerMonth(),
            'basic' => $this->getBasicPackageCostPerMonth(),
            default => 0,
        };

        // Create invoice
        Invoice::create([
            'user_id' => $user->id,
            'amount' => $amount * $request->period_in_months,
            'subscription_id' => $subscription->id,
            'type' => 'subscription',
        ]);

        return response([
            'status' => true,
            'message' => "Your subscription has been registered for {$request->period_in_months} " . ($request->period_in_months == 1 ? 'month' : 'months') . ".",
            'expires_at' => $expiresAt->toDateTimeString(),
        ], 200);
    }



    // public function freeSubscription(Request $request)
    // {
    //     if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {

    //         $user = $request->user();


    //         $currentSubscription = Subscription::where('user_id', $user->id)->where('is_online', 1)->first();

    //         if (!$currentSubscription) {

    //             $package_name = Option::where('key', 'package_for_guest')->first()->value;
    //             $free_trial_days = Option::where('key', 'free_trial_period_in_days')->first()->value;

    //             $package = Package::where('name', $package_name)->first();
    //             $guestSubscription = Subscription::where('user_id', $user->id)
    //                 ->where('type', 'guest_subscription')->first(); // free subscription only once for each guest

    //             if ($package) {
    //                 if (!$guestSubscription) {
    //                     Subscription::create([
    //                         'user_id' => $user->id,
    //                         'package_id' => $package->id,
    //                         'type' => 'guest_subscription',
    //                         'expires_at' => now()->addDays($free_trial_days), // Add free trial days

    //                     ]);

    //                     return response([
    //                         'status' => true,
    //                         'message' => 'You have been registered for the free plan for ' . $free_trial_days . ' days',
    //                     ], 200);
    //                 } else {
    //                     return response([
    //                         'status' => false,
    //                         'message' => 'The user has been subscribed to the free plan before',
    //                     ], 200);
    //                 }
    //             } else {

    //                 $basic_package = Package::where('name', 'basic')->first();
    //                 Subscription::create([
    //                     'user_id' => $user->id,
    //                     'package_id' => $basic_package->id,
    //                     'type' => 'guest_subscription',
    //                     'expires_at' => now()->addDays($free_trial_days), // Add free trial days
    //                 ]);
    //             }
    //         } else {
    //             return response(['status' => false, 'message' => 'The user is currently subscribed'], 200);
    //         }
    //     } else {
    //         return response(['status' => false, 'message' => 'Token is expired'], 200);
    //     }
    // }

    public function currentSubscriptionDetails(Request $request)
    {
        if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {

            $user = $request->user();
            $currentSubscription = $user->subscriptions()->where('is_online', 1)->first();
            $isOnline = false;
            $expiryDate = null;
            $createdAt = null;

            if ($currentSubscription) {
                $packageData = Package::where('id', $currentSubscription->package_id)->first();
                $createdAt = \Carbon\Carbon::parse($currentSubscription->created_at)->format('Y-m-d');

                // Check for user_subscription type and period_in_months
                if ($currentSubscription->type === 'user_subscription') {
                    if ($currentSubscription->period_in_months !== null) {
                        $expiryDate = \Carbon\Carbon::parse($currentSubscription->created_at)
                            ->addMonths($currentSubscription->period_in_months)
                            ->format('Y-m-d');
                    } else {
                        // Set period_in_months to 1 if it's null
                        $currentSubscription->period_in_months = 1;
                        $expiryDate = \Carbon\Carbon::parse($currentSubscription->created_at)
                            ->addMonths(1)
                            ->format('Y-m-d');
                    }
                    $isOnline = now()->lessThanOrEqualTo($expiryDate);
                } elseif ($currentSubscription->type === 'guest_subscription') {
                    if (!$currentSubscription->is_online || now()->greaterThan($currentSubscription->expires_at)) {
                        $currentSubscription->update(['is_online' => false]);
                        $isOnline = false;
                    }
                    $isOnline = true;
                } else {
                    $isOnline = true;
                }

                // Update the current subscription's online status if necessary
                if ($currentSubscription->is_online !== $isOnline) {
                    $currentSubscription->is_online = $isOnline;
                    $currentSubscription->save(); // Save the updated status
                }
                // Check if the subscription is expired
                if (!$isOnline) {
                    return response([
                        'status' => false,
                        'message' => 'The subscription has expired',
                        'data' => []
                    ], 200);
                }
                return response([
                    'status' => true,
                    'message' => 'Current subscription details retrieved successfully',
                    'data' => [
                        'user_id' => $currentSubscription->user_id,
                        'subscription_type' => $currentSubscription->type,
                        'package_id' => $currentSubscription->package_id,
                        'package_name' => $packageData->name ? ucfirst($packageData->name) : null,
                        'package_desc' => $packageData->description,
                        'is_online' => $isOnline,
                        'subscription_date' => $createdAt,
                        'expiry_date' => $expiryDate,
                    ],

                ], 200);
            } else {
                $currentSubscription = $user->subscriptions()->where('is_online', 0)->first();
                $createdAt = \Carbon\Carbon::parse($currentSubscription->created_at)->format('Y-m-d');

                if ($currentSubscription) {
                    // Check if there is a last package
                    $lastPackage = Package::find($currentSubscription->package_id);
                    return response([
                        'status' => false,
                        'message' => 'No current subscription, but you have a last package',
                        'data' => [
                            'user_id' => $currentSubscription->user_id,
                            'subscription_type' => $currentSubscription->type,
                            'package_id' => $currentSubscription->package_id,
                            'package_name' => $lastPackage ? ucfirst($lastPackage->name) : 'null',
                            'package_desc' => $lastPackage ? $lastPackage->description : 'No description available',
                            'is_online' => $isOnline,
                            'subscription_date' => $createdAt,
                        ]
                    ], 200);
                } else {
                    return response([
                        'status' => false,
                        'message' => 'No subscriptions found',
                        'data' => []
                    ], 200);
                }
            }
        } else {
            return response([
                'status' => false,
                'message' => 'Token is expired',
                'data' => []
            ], 200);
        }
    }
}
