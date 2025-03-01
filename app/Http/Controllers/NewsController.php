<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $news = News::orderBy('created_at', 'desc')->get();

        return view('news.index', compact('news'));
    }

    public function create()
    {

        return view('news.create');
    }

    private function validatedData(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $additional = [];

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '_' . $image->getClientOriginalName();

            $destinationPath = public_path('images/newsImages');
            if (!file_exists($destinationPath . '/' . $imageName)) {
                $image->move(public_path('images/newsImages'), $imageName);
            }
            $additional = [
                'img' => $imageName,
            ];
        }

        $validated = array_merge($data, $additional);
        return $validated;
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        News::create($data);

        return redirect()->route('news.index')->with('success', 'news was created successfully');
    }

    public function edit($uuid)
    {

        $news = News::where('uuid', $uuid)->first();
        return view('news.edit', compact('news'));
    }


    public function update(Request $request, $uuid)
    {
        $news = News::where('uuid', $uuid)->first();

        $additional = ['is_online' => $request->has('is_online') ? 1 : 0];
        $data = $this->validatedData($request);

        $finalData = array_merge($data, $additional);

        $news->update($finalData);
        return redirect()->route('news.index')->with('success', 'news was updated successfully');
    }
}
