<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsApi extends Controller
{
    public function getAllNews()
    {

        $news = News::all();

        return response([
            'status' => true,
            'data' => $news
        ], 200);
    }
}
