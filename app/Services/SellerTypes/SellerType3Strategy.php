<?php

namespace App\Services\SellerTypes;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;

class SellerType3Strategy implements SellerTypeStrategy
{
    public function handle(Request $request, Store $store)
    {
        // No action needed for now, discount applies only to future offers.
    }
}
