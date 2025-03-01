<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $packages = Package::orderBy('created_at','desc')->get();
        return view('packages.index',compact('packages'));
    }

    public function create(){

        return view('packages.create');
    }

    public function validateData(Request $request){
        $data = $request->validate([
            'description'=>'required',

        ]);



        return $data ;
    }

    // public function store(Request $request){

    //     $data = $this->validateData($request);
    //     Package::create($data);

    //     return redirect()->route('packages.index')->with('success','package was created successfully');

    // }

    public function edit($uuid){
        $package = Package::where('uuid',$uuid)->first();
        return view('packages.edit',compact('package'));
    }

    public function update(Request $request , $uuid){
        $package = Package::where('uuid',$uuid)->first();

        $data = $this->validateData($request);

        $is_online = ['is_online'=>$request->has('is_online')?1:0];

        $finalData = array_merge($data,$is_online);

        $package->update($finalData);

        return redirect()->route('packages.index')->with('success','package was updated successfully');
    }

    // show the sections that belong to a certain package

    public function showSections($uuid){
        $package = Package::where('uuid',$uuid)->first();

        $sections = $package->sections;
        return view('packages.packageSections',compact('package','sections'));
    }
}
