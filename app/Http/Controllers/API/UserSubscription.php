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

    public function getSuperPackageCostPerMonth(){
        $option = Option::where('key','super_cost_per_month')->first();
        if($option){
            $super_cost_per_month = floatval($option->value);
            return $super_cost_per_month ;
        }
        else{
            return 59.99;
        }
    }


    public function getBasicPackageCostPerMonth(){
        $option = Option::where('key','basic_cost_per_month')->first();
        if($option){
            $basic_cost_per_month = floatval($option->value);
            return $basic_cost_per_month ;
        }
        else{
            return 20.99;
        }
    }


    public function getElitePackageCostPerMonth(){
        $option = Option::where('key','elite_cost_per_month')->first();
        if($option){
            $elite_cost_per_month = floatval($option->value);
            return $elite_cost_per_month ;
        }
        else{
            return 70.99;
        }
    }

    public function packageUserSupscriptionMonthly(Request $request){

       
        if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){
        
            $request->validate([
                'package_id'=>'required',
            ]);

            $user = $request->user();
            $subscription = Subscription::create([

                'user_id' => $user->id ,
                'package_id'=>$request->package_id ,
                'period_in_months'=>1,
                'type'=>'user_subscription',

            ]);

            $amount = 0;
            if(Package::findOrFail($request->package_id)->name =='super'){
                $amount = $this->getSuperPackageCostPerMonth();

            }
            elseif(Package::findOrFail($request->package_id)->name =='elite'){
                $amount = $this->getElitePackageCostPerMonth();

            }
            elseif(Package::findOrFail($request->package_id)->name =='basic'){

                $amount = $this->getBasicPackageCostPerMonth();

            }
        
            Invoice::create([
                'user_id'=>$user->id ,
                'amount' => $amount,
                'subscription_id'=>$subscription->id,
                'type'=>'subscription',
            ]);


            return response(['message'=>'تم تسجيل اشتراكك في الباقة لمدة شهر ']);
        
        
        }

        else{
            return response(['message'=>'token is expired']);

        }
    }


    public function freeSubscription(Request $request){
        if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){
    
            $user = $request->user();


            $currentSubscription = Subscription::where('user_id',$user->id)->where('is_online',1)->first();
         if(!$currentSubscription){



            $package_name = Option::where('key','package_for_guest')->first()->value;
            $package = Package::where('name',$package_name)->first();
            $guestSubscribtion = Subscription::where('user_id',$user->id)
            ->where('type','guest_subscription')->first(); // free subscribtion only once for each guest
    
            if($package){
                if(!$guestSubscribtion){
                Subscription::create([
                    'user_id'=>$user->id ,
                    'package_id' => $package->id ,
                    'type'=>'guest_subscription',
                    
                ]);

                return response(['message'=>'تم تسجيلك في الخطة المجانية لمدة 7 أيام']);

            }
               else{
                return response(['message'=>'the user has been subscribed for the free plan before']);
            }
    
    
        }
    
           else{
    
            $basic_package = Package::where('name','basic')->first();
            Subscription::create([
                'user_id'=>$user->id ,
                'package_id' => $basic_package->id ,
                'type'=>'guest_subscription',
            ]);  



           
        
        
        }
            }
            else{
                return response(['message'=>'the user is currently subscribed']);
                
            }
       
       
    }

                else{
                    return response(['message'=>'token is expired']);

                }
    
    }

    public function currentSubscriptionDetails(Request $request){
        if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){
        
            $user = $request->user();
            $currentSubscription = $user->subscriptions()->where('is_online',1)->first();
            if($currentSubscription){
                return response(['current subscription'=>$currentSubscription]);
            }

            else{
                return response(['message'=>'no current subscription']);
            }
        
        }

        else{
        return response(['message'=>'token is expired']);
        }
    }



}