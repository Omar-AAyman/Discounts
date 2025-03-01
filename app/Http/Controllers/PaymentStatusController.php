<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
  public function show(Request $request)
  {
    $request = request();


    $message = $request->message ?? null;

    if ($request->status === "success" || $request->status === "paid") {
      return view("payment-status-pages.payment-success", [
        "message" => $message ?? "Your payment has been processed successfully."
      ]);
    } else {
      return view("payment-status-pages.payment-failure", [
        "message" => $message ?? "Your payment failed. Please try again."
      ]);
    }
  }
}
