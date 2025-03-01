<?php

namespace App\Http\Controllers;

use App\Models\OnBoarding;
use Illuminate\Http\Request;

class OnBoardingController extends Controller
{

    public function index(){
        $onBoardings = OnBoarding::all();
        return view('onBoardings.index',compact('onBoardings'));

    }
    public function edit($id){
        $onBoarding = OnBoarding::findOrFail($id);
        return view('onBoardings.edit',compact('onBoarding'));

    }

    public function update(Request $request , $id){
        $data = $request->validate([
            'image_url'=>'required|image',
            'title'=>'required',
            'subtitle'=>'required',
            'textbutton'=>'required',
        ]);

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/onBoardingImages'), $imageName);
            $data['image_url'] = $imageName;
        }

        $onBoarding = OnBoarding::findOrFail($id);
        $onBoarding->update($data);
        return redirect()->route('onboardings.index')->with('success','updated successfully');
    }
}
