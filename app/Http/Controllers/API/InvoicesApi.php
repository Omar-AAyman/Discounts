<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;


class InvoicesApi extends Controller
{
    public function myPurchases(Request $request){
        if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){

            $user = $request->user();
            $invoices = Invoice::where('type','product')->where('status','paid')->with('product')->get();
            $totalBeforeDiscount = $invoices->sum('amount');

            $totalAfterDiscount = 0;
            foreach($invoices as $invoice){
                if($invoice->product->offer_id && $invoice->product->offer->is_online){
                    for($i=0 ; $i< $invoice->quantity ; $i++){
                       $totalAfterDiscount += $invoice->product->price * ($invoice->product->offer->discount_percentage/100);
                    }
                }
                else{
                    $totalAfterDiscount += $invoice->amount ;
                }
            }
            return response(['paid invoices'=>$invoices 
        ,'total price before discount'=>$totalBeforeDiscount 
    ,'total price after discount'=>$totalAfterDiscount]);
        }

        else{
            return response(['message'=>'token is expired']);
        }
    }
}
