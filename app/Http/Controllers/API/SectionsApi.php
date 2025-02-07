<?php

namespace App\Http\Controllers\API;

use App\Models\Section;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;


class SectionsApi extends Controller
{
    public function sections()
    {
        $sections = Section::where('is_online',1)->get();
        return response(['sections' => $sections]);
    }
}
