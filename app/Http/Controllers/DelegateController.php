<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Section;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class DelegateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    // // retrieve the sellers that a specific delegate has added to the system
    // public function delegateSellers(){


    //     $delegate = User::where('id',auth()->user()->id)->first();
    //     $sellers = User::where('delegate_id',$delegate->id)->get();

    //     return view('delegates.delegateSellers',compact('delegate','sellers'));


    // }

    // main view for delegates

    public function mainView(){
        return view('delegate');
    }

    // show the view of adding a seller form
    public function createSeller(){
        $sections = Section::where('is_online',1)->get();
        return view('create-seller',compact('sections'));
    }

    public function addSeller(Request $request){
        $request->validate([
            'seller_name' => 'required',
            'store_name' => 'required',
            'section_id' => 'required',
           
            'sector_representative' => 'required',
            'location' => 'required',
            'phone_number1' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'working_hours' => 'required|min:1',
            'working_days' => 'required|min:1',
 
        ]);

        // Create a new Store instance
        $store = new Store();

        // Map form input names to model properties
        $store->seller_name = $request->input('seller_name');
        $store->name = $request->input('store_name');
        $store->section_id = $request->input('section_id');
        $store->licensed_operator_number = $request->input('licensed_operator_number');
        $store->sector_representative = $request->input('sector_representative');
        $store->location = $request->input('location');
        $store->phone_number1 = $request->input('phone_number1');
        $store->phone_number2 = $request->input('phone_number2');
        $store->email = $request->input('email');
        $store->work_hours = $request->input('working_hours');
        $store->work_days = $request->input('working_days');
        $store->facebook = $request->input('facebook');
        $store->instagram = $request->input('instagram');

        // Handle image uploads (optional)
        if ($request->hasFile('contract_img')) {
            $image = $request->file('contract_img');
            $imageName = $image->getClientOriginalName();

            $destinationPath = public_path('ContractImages'); 
            if (!file_exists($destinationPath . '/' . $imageName)) {

            $image->move(public_path('ContractImages'), $imageName);
            }
          $store->contract_img = $imageName;
   }

        if ($request->hasFile('store_img')) {
            $image = $request->file('store_img');
            $imageName = $image->getClientOriginalName();

            $destinationPath = public_path('StoreImages'); 
            if (!file_exists($destinationPath . '/' . $imageName)) {

            $image->move(public_path('StoreImages'), $imageName);
            }
        $store->store_img = $imageName;
        }

        $store->status = 'pending';
        $store->delegate_id = auth()->user()->id;
        $store->save();

        // Handle success or failure
        return redirect()->route('delegates.mainView')->with('success', 'request of adding  seller to the system was send successfully');

    }



}