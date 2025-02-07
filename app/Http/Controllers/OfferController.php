<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\OfferNotification;
use App\Models\Store;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::all();
        return view('offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stores = Store::where('is_online',1)->get();
        return view('offers.create',compact('stores'));
    }


    public function validateData(Request $request){
        $data = $request->validate([
            'title'=>'required',
            'discount_percentage'=>'required',
            'price_before_discount'=>'required',

        ]);

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('offerImages'), $imageName);
            $data['img'] = $imageName;
           
        }

        if ($request->hasFile('bg_img')) {
            $image = $request->file('bg_img');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('offerImages'), $imageName);
            $data['bg_img'] = $imageName;
           
        }

        if($request->has('exclusions')){
            $data['exclusions'] = $request->exclusions ;
        }

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['store_id'=>'required',
    ]);

        $data = $this->validateData($request);
        $data['store_id'] = $request->store_id;
       
        Offer::create($data);

        return redirect()->route('offers.index')->with('success', 'Offer created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)

    {
        $offer = Offer::findOrFail($id);
        return view('offers.edit', compact('offer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $offer = Offer::findOrFail($id);
        $data = $this->validateData($request);

        $data['is_online'] = $request->has('is_online')?1:0;

        $offer->update($data);

        return redirect()->route('offers.index')->with('success', 'Offer updated successfully.');
    }

     
 
    
}
