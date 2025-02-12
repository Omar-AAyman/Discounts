<?php
namespace App\Services\SellerTypes;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class SellerType1Strategy implements SellerTypeStrategy
{
    public function handle(Request $request, Store $store)
    {
        Store::where('id', $store->id)->update([
            'discount_percentage' => $request->discount_percentage,
        ]);
    }
}

