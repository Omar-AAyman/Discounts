<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    // display 'free for once' guest subscriptions

    public function displayGuestsSubscribtions()
    {
        $subscribtions = Subscription::where('type', 'guest_subscription')->orderBy('created_at', 'desc')->get();
        $packageName = Option::where('key', 'package_for_guest')->first();

        return view('subscribtions.guestSubscribtions', compact('subscribtions', 'packageName'));
    }

    // display users subscriptions

    public function displayUsersSubscribtions()
    {
        $subscribtions = Subscription::where('type', 'user_subscription')->get();

        // Update expired subscriptions
        foreach ($subscribtions as $subscribtion) {
            if ($subscribtion->expires_at < now()) {
                $subscribtion->update(['is_online' => false]);
            }
        }

        return view('subscribtions.userSubscribtions', compact('subscribtions'));
    }

    // display view for subscribe a guest
    public function showSubscribeGuest()
    {
        $users = User::where('type', 'client')->get();
        return view('subscribtions.showSubscribeGuest', compact('users'));
    }
    // guest benifits only once , no subscription period
    public function guestSubscription(Request $request)
    {

        $request->validate(['user_id' => 'required']);
        $userId = $request->input('user_id');
        $user = User::findOrFail($userId);

        $package_name = Option::where('key', 'package_for_guest')->first()->value;
        $period_in_days = Option::where('key', 'free_trial_period_in_days')->first()->value;
        $package = Package::where('name', $package_name)->first();
        $guestSubscribtion = Subscription::where('user_id', $user->id)
            ->where('type', 'guest_subscription')->first(); // free subscribtion only once for each guest

        if ($package) {
            if (!$guestSubscribtion) {
                Subscription::create([
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'type' => 'guest_subscription',
                    'expires_at' => now()->addDays($period_in_days),
                    'status' => 'manually',
                ]);
            } else {
                return redirect()->back()->with('subscribtionError', 'this guest is currently subscribed as a guest or has been subscribed as a guest before');
            }
        } else {

            $basic_package = Package::where('name', 'basic')->first();
            Subscription::create([
                'user_id' => $user->id,
                'package_id' => $basic_package->id,
                'type' => 'guest_subscription',
                'expires_at' => now()->addDays($period_in_days),
                'status' => 'manually',
            ]);
        }

        return redirect()->route('subscriptions.guestSubscriptions');
    }

    public function unsubscribe(Request $request)
    {
        $userId = $request->input('userId');
        $user = User::findOrFail($userId);
        $userSubscription = Subscription::where('user_id', $user->id)->where('is_online', 1)->first();
        $userSubscription->is_online = 0; // end subscription
        $userSubscription->save();

        return redirect()->back()->with('success', 'successfully unsubscribed');
    }



    // show subscribe a user view
    public function showSubscribeUser()
    {
        $users = User::where('type', 'client')->get();
        $packages = Package::where('is_online', 1)->get();
        return view('subscribtions.showSubscribeUser', compact('users', 'packages'));
    }


    // customer/user subscription
    public function userSubscription(Request $request)
    {
        $request->validate(['user_id' => 'required', 'package_id' => 'required', 'period_in_months' => 'required']);
        $userId = $request->input('user_id');
        $user = User::findOrFail($userId);

        $packageId = $request->input('package_id');

        $package = Package::findOrFail($packageId);

        $userSubscription = Subscription::where('user_id', $user->id)
            ->where('type', 'user_subscription')
            ->where('is_online', 1)->first();


        if (!$userSubscription) {
            Subscription::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'type' => 'user_subscription',
                'period_in_months' => $request->input('period_in_months'),
                'expires_at' => now()->addMonths($request->input('period_in_months')),
                'status' => 'manually',
            ]);

            return redirect()->route('subscriptions.userSubscriptions');
        } else {
            return redirect()->back()->with('subscribtionError', 'this user is currently subscribed');
        }
    }


    
}
