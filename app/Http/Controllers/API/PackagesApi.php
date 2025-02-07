<?php

namespace App\Http\Controllers\API;

use App\Models\Package;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;


class PackagesApi extends Controller
{
    public function packages()
    {
        $packages = Package::where('is_online',1)->get();
        return response(['packages' => $packages]);
    }
}
