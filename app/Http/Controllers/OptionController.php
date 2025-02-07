<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $options = Option::whereNull('img')->get();
        return view('options.index',compact('options'));
    }

    public function create(){

        return view('options.create');

    }

    public function validateData(Request $request){
        $data = $request->validate([
            'key'=>'required',
            'value'=>'required',
        ]);

        return $data ;
    }

    public function store(Request $request){

        $data = $this->validateData($request);
        Option::create($data);

        return redirect()->route('options.index')->with('success','option was created successfully');
    }

    public function edit($id){
        $option = Option::findOrFail($id);
        return view('options.edit',compact('option'));
    }

    public function update(Request $request , $id){
        $option = Option::findOrFail($id);
        $data = $this->validateData($request);

        $option->update($data);
        return redirect()->route('options.index')->with('success','option was updated successfully');


    }

    // display options with images
    public function showImages(){

        $options = Option::whereNotNull('img')->get();
        return view('options.showImages',compact('options'));
        
    }

    // show edit image view 
    public function editImageOption($id){
        $option = Option::findOrFail($id);
        return view ('options.editImage',compact('option'));
    }

    public function updateImageOption(Request $request , $id){
        $option = Option::findOrFail($id);

        if($request->hasFile('img')){
            $image = $request->file('img');
            $imageName = time().'.'.$image->getClientOriginalName();
           
            $image->move(public_path('optionImages'), $imageName);
            $option->img = $imageName;
            $option->save();
  
        }

        return redirect()->route('options.showImages')->with('success','image was updated successfully');
    }

    
}
