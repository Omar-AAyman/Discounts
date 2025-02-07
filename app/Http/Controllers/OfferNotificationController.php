<?php

namespace App\Http\Controllers;

use App\Models\OfferNotification;
use Illuminate\Http\Request;

class OfferNotificationController extends Controller
{
    // show change discount percentage requests
    public function showChangeDiscountRequests(){
        $changeRequests = OfferNotification::where('status','pending')
        ->orderBy('created_at','desc')->get();
        return view('offerNotifications.pending',compact('changeRequests'));
    }

    public function acceptChangeDiscountRequest($id){
        $offerNotification = OfferNotification::findOrFail($id);
        $offerNotification->status = 'approved';
        $offerNotification->save();
        $offer = $offerNotification->offer ;
        $offer->discount_percentage = $offerNotification->new_discount_percentage;
        $offer->save();

        return redirect()->back()->with('success','request was approved');

    }

    public function rejectChangeDiscountRequest($id){
        $offerNotification = OfferNotification::findOrFail($id);
        $offerNotification->status = 'rejected';
        $offerNotification->save();

        return redirect()->back()->with('fail', 'Request was rejected');

    }
}
