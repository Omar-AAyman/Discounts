<?php

namespace App\Services\SellerTypes;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class SellerType4Strategy implements SellerTypeStrategy
{
    public function handle(Request $request, Store $store)
    {
        // No action needed for now, discount applies only to future offers.
    }
}
