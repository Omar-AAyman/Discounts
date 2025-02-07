<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Section;
use App\Models\Store;
use Illuminate\Http\Request;
use Random\Engine\Secure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $sections = Section::orderBy('created_at','desc')->get();
        return view('sections.index',compact('sections'));
    }

    public function create(){
        $packages = Package::where('is_online',1)->get();

        return view('sections.create',compact('packages'));
    }

    public function validateData(Request $request){
        $data = $request->validate([
            'name'=>'required',
            'description'=>'required',
            'type'=>'required',
            'package_id'=>'required',


        ]);

        

        return $data ;
    }

    public function store(Request $request){

        $data = $this->validateData($request);
        Section::create($data);

        return redirect()->route('sections.index')->with('success','section was created successfully');

    }

    public function edit($uuid){
        $section = Section::where('uuid',$uuid)->first();
        $packages = Package::where('is_online',1)->get();

        return view('sections.edit',compact('section','packages'));
    }

    public function update(Request $request , $uuid){
        $section = Section::where('uuid',$uuid)->first();
        $data = $this->validateData($request);

        $is_online = ['is_online'=>$request->has('is_online')?1:0];

        $finalData = array_merge($data,$is_online);

       
        $section->update($finalData);

        return redirect()->route('sections.index')->with('success','section was updated successfully');
    }

    // display stores that belong to a certain section

    public function showStores($uuid){
       
         $section = Section::where('uuid',$uuid)->first();
        
        $stores = $section->stores()->orderBy('points','desc')->get() ;
        return view('sections.showStores', compact('section','stores'));
    }

    // return the view to attach a store to a section

    public function showAttachStore($uuid){
        $section = Section::where('uuid',$uuid)->first();
        $stores = Store::whereNull('section_id')->get();

        return view('sections.showAttachStore',compact('section','stores'));
    }

    // attach a store to a section 

    public function attachStore(Request $request ,$uuid){
        $store = Store::findOrFail($request->input('store_id'));
        $section = Section::where('uuid',$uuid)->first();

        if($store){

            $store->section_id = $section->id;
            $store->save();

            return redirect()->route('sections.showStores',$uuid);
            
        }

        else{

            throw new NotFoundHttpException();
        }

    }


}
