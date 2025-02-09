<?php

namespace App\Services\SellerTypes;

use App\Models\Store;
use Illuminate\Http\Request;

interface SellerTypeStrategy
{
    public function handle(Request $request, Store $store);
}
