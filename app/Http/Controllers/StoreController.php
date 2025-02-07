<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $stores = Store::where('status','approved')->orderBy('created_at','desc')->get();
        return view('stores.index',compact('stores'));
    }

    public function create(){

        $users = User::where('is_online',1)->where('type','seller')->get();
        $sections = Section::where('is_online',1)->get();
        return view('stores.create',compact('users','sections'));
    }

    public function validateData(Request $request){
        $data = $request->validate([
            'name'=>'required',
            'description'=>'required',
            'user_id'=>'required',
            'section_id'=>'required',

        ]);

        

        return $data ;
    }

    public function store(Request $request){

        $data = $this->validateData($request);
        Store::create($data);

        return redirect()->route('stores.index')->with('success','store was created successfully');

    }

    public function edit($uuid){
        $store = Store::where('uuid',$uuid)->first();
        $sections = Section::where('is_online',1)->get();
        return view('stores.edit',compact('store','sections'));
    }

    public function update(Request $request , $uuid){
        $store = Store::where('uuid',$uuid)->first();
        $data = $this->validateData($request);

        $is_online = ['is_online'=>$request->has('is_online')?1:0];

        $finalData = array_merge($data,$is_online);

        $store->update($finalData);

        return redirect()->route('stores.index')->with('success','store was updated successfully');
    }


    // show the delegates requests for adding new sellers
    public function showSellersRequests(){
        $stores = Store::where('status','pending')->orderBy('created_at','desc')->get();
        return view('stores.show-sellers-requests',compact('stores'));
    }

    public function approveSeller(Request $request){
        $store_id = $request->store_id;
        $store = Store::findOrFail($store_id);
        return view('stores.addSellerAsUser',compact('store'));

    }

    public function addSeller(Request $request){
        $request->validate([
            'last_name'=>'required',
            'password'=>['required', 'string', 'min:8', 'confirmed'],
        ]);

        $store_id = $request->store_id;
        $store = Store::findOrFail($store_id);

        $user = User::create([
            'first_name'=> $store->seller_name,
            'last_name'=>$request->last_name,
            'email'=>$store->email,
            'password'=>Hash::make($request->password),
            'phone'=>$store->phone_number1,
            'phone2'=> isset($store->phone_number2)?$store->phone_number2:null,
            'type'=>'seller',
            'facebook'=>isset($store->facebook)?$store->facebook:null,
            'instagram'=>isset($store->instagram)?$store->instagram:null,

        ]);


        $store->status = 'approved';
        $store->user_id = $user->id;
        $store->save();

        return redirect()->route('stores.showSellersRequests')->with('success','seller and store were added successfully');


    }
}
