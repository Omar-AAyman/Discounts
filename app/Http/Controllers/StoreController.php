<?php

namespace App\Http\Controllers;

use App\Models\DiscountRequest;
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

    public function index()
    {

        $stores = Store::where('status', 'approved')->with('delegate')->orderBy('created_at', 'desc')->get();
        return view('stores.index', compact('stores'));
    }

    public function create()
    {

        $users = User::where('is_online', 1)->where('type', 'seller')->get();
        $sections = Section::where('is_online', 1)->get();
        return view('stores.create', compact('users', 'sections'));
    }

    public function validateData(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'user_id' => 'required',
            'section_id' => 'required',

        ]);



        return $data;
    }

    public function store(Request $request)
    {

        $data = $this->validateData($request);
        Store::create($data);

        return redirect()->route('stores.index')->with('success', 'store was created successfully');
    }

    public function edit($uuid)
    {
        $store = Store::where('uuid', $uuid)->first();
        $sections = Section::where('is_online', 1)->get();
        return view('stores.edit', compact('store', 'sections'));
    }

    public function update(Request $request, $uuid)
    {
        $store = Store::where('uuid', $uuid)->first();
        $data = $this->validateData($request);

        $is_online = ['is_online' => $request->has('is_online') ? 1 : 0];

        $finalData = array_merge($data, $is_online);

        $store->update($finalData);

        return redirect()->route('stores.index')->with('success', 'store was updated successfully');
    }


    // show the delegates requests for adding new sellers
    public function showSellersRequests()
    {
        $stores = Store::where('status', 'pending')->orderBy('created_at', 'desc')->get();
        return view('stores.show-sellers-requests', compact('stores'));
    }

    public function approveSeller(Request $request)
    {
        $store_id = $request->store_id;
        $store = Store::findOrFail($store_id);

        $store->status = 'approved';
        $store->save();

        return redirect()->route('stores.showSellersRequests')->with('success', 'Seller and Store Request were approved successfully');
    }

    public function showChangeDiscountRequests()
    {
        $changeRequests = DiscountRequest::where('status', 'pending')->with('store')
            ->orderBy('created_at', 'desc')->get();

        return view('stores.pending-requests', compact('changeRequests'));
    }

    public function acceptChangeDiscountRequest($id)
    {
        $discountRequest = DiscountRequest::findOrFail($id);
        $discountRequest->status = 'approved';
        $discountRequest->save();
        $store = $discountRequest->store;
        $store->discount_percentage = $discountRequest->requested_discount_percentage;
        $store->save();

        return redirect()->back()->with('success', 'request was approved');
    }

    public function rejectChangeDiscountRequest($id)
    {
        $discountRequest = DiscountRequest::findOrFail($id);
        $discountRequest->status = 'rejected';
        $discountRequest->save();

        return redirect()->back()->with('fail', 'Request was rejected');
    }


    public function deleteRequests()
    {
        $stores = Store::where('status', 'delete_requested')->get();
        return view('stores.delete-requests', compact('stores'));
    }

    public function processDeleteRequest(Request $request, Store $store)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($request->action === 'approve') {
            // Delete the store or mark it as deleted
            $store->delete();

            // Optionally, delete the associated user (if needed)
            $store->user()->delete();
        } elseif ($request->action === 'reject') {
            // Reject the request and restore the store to active status
            $store->status = 'approved';
            $store->save();
        }

        return redirect()->back()->with('success', 'Store deletion request processed successfully.');
    }
    public function requestDelete(Request $request, Store $store)
    {
        // Ensure the authenticated user owns the store or has permission
        if ($store->delegate->id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        // Check if deletion is already requested
        if ($store->status === 'delete_requested') {
            return redirect()->back()->with('error', 'Delete request already submitted.');
        }

        // Update status to 'delete_requested'
        $store->status = 'delete_requested';
        $store->save();

        return redirect()->back()->with('success', 'Delete request submitted successfully.');
    }
}
