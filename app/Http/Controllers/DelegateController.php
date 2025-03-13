<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Store;
use App\Models\Section;
use App\Models\SellerType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\SellerResource;
use App\Models\Country;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
class DelegateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get all sellers related to the authenticated delegate.
     *
     * @return \Illuminate\View\View
     */
    public function getRelatedSellers()
    {
        $delegateId = auth()->id();

        $stores = Store::where('delegate_id', $delegateId)
            ->with(['branches', 'products'])
            ->get();

        $sellers = collect();
        if (!$stores->isEmpty()) {
            $sellers = User::whereIn('id', $stores->pluck('user_id'))
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($seller) use ($stores) {
                    $store = $stores->firstWhere('user_id', $seller->id);
                    return [
                        'seller' => $seller,
                        'store' => $store
                    ];
                });
        }
        // dd($sellers);
        return view('delegates.sellers', compact('sellers'));
    }


    // main view for delegates

    public function mainView()
    {
        return view('delegates.delegate');
    }

    // show the view of adding a seller form
    public function createSeller()
    {
        $sections = Section::where('is_online', 1)->get();
        $sellerTypes = SellerType::all();
        $countries = Country::with('cities')->orderBy('name')->get(); // Eager load cities

        return view('delegates.create-seller', compact('sections', 'sellerTypes', 'countries'));
    }

    public function addSeller(Request $request)
    {
        $request->validate([
            'seller_first_name' => 'required',
            'seller_last_name' => 'required',
            'store_name' => 'required',
            'section_id' => 'required|exists:sections,id',
            'seller_type' => 'required|exists:seller_types,id',
            'sector_representative' => 'required',
            'location' => 'required',
            'phone_number1' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'working_hours_from' => 'required|min:1',
            'working_hours_to' => 'required|min:1',
            'working_days' => 'required|min:1',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
        ]);
        $seller = new User();
        $seller->first_name = $request->seller_first_name;
        $seller->last_name = $request->seller_last_name;
        $seller->email = $request->email;
        $seller->phone = $request->phone_number1;
        $seller->phone2 = $request->phone_number2;
        $seller->type = 'seller';
        $seller->seller_type_id = $request->seller_type;
        $seller->password = Hash::make('password');
        $seller->city = $request->city_id;
        $seller->country = $request->country_id;
        $seller->is_seller = 1;
        $seller->facebook = $request->facebook;
        $seller->instagram = $request->instagram;
        $seller->save();

        // Create a new Store instance
        $store = new Store();

        // Map form input names to model properties
        $store->user_id = $seller->id;
        $store->seller_name = $request->seller_first_name . ' ' . $request->seller_last_name;
        $store->name = $request->input('store_name');
        $store->section_id = $request->input('section_id');
        $store->licensed_operator_number = $request->input('licensed_operator_number');
        $store->sector_representative = $request->input('sector_representative');
        $store->location = $request->input('location');
        $store->phone_number1 = $request->input('phone_number1');
        $store->phone_number2 = $request->input('phone_number2');
        $store->email = $request->input('email');
        $store->work_hours = date('h:i A', strtotime($request->working_hours_from)) . ' - ' .
            date('h:i A', strtotime($request->working_hours_to));
        $store->work_days = json_encode($request->working_days);
        $store->facebook = $request->input('facebook');
        $store->instagram = $request->input('instagram');
        $store->delegate_id = auth()->id();
        $seller->city = $request->city_id;
        $seller->country = $request->country_id;

        // Handle image uploads
        $this->handleImageUploads($request, $store);

        $store->status = 'pending';
        $store->user_id = $seller->id;
        $store->save();

        // Generate QR code for the store
        $this->generateStoreQrCode($store);

        // Handle success or failure
        return redirect()->route('delegates.mainView')->with('success', 'request of adding seller to the system was send successfully');
    }


    private function handleImageUploads(Request $request, Store $store)
    {
        // Handle contract image upload
        if ($request->hasFile('contract_img')) {
            $this->deleteOldImage($store->contract_img, 'images/contractImages');
            $store->contract_img = $this->uploadImage($request->file('contract_img'), 'images/contractImages');
        }

        // Handle store image upload
        if ($request->hasFile('store_img')) {
            $this->deleteOldImage($store->store_img, 'images/storeImages');
            $store->store_img = $this->uploadImage($request->file('store_img'), 'images/storeImages');
        }

        // Save updated store data
        $store->save();
    }

    /**
     * Deletes an old image if it exists.
     */
    private function deleteOldImage(?string $imageName, string $directory): void
    {
        $imagePath = public_path("$directory/$imageName");

        if ($imageName && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    /**
     * Uploads a new image and returns the file name.
     */
    private function uploadImage(UploadedFile $image, string $directory): string
    {
        $imageName = hash('sha256', time() . Str::random(10)) . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path($directory);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $image->move($destinationPath, $imageName);
        return $imageName;
    }
    // New method to generate QR code for the store
    private function generateStoreQrCode(Store $store): void
    {
        // Ensure the store has a UUID
        if (!$store->uuid) {
            $store->uuid = Str::uuid();
            $store->save();
        }

        // Convert hex color to RGB (C01A86)
        $color = sscanf('#C01A86', "#%02x%02x%02x");
        [$red, $green, $blue] = $color;

        // Generate QR content with UUID-based URL
        $qrContent = $store->user->seller_type_id.'_'.$store->uuid;

        // Generate the QR code with custom color
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->color((int)$red, (int)$green, (int)$blue)
            ->generate($qrContent);

        // Define paths
        $directory = public_path('images/qrcodes');
        $filename = "store_{$store->uuid}.svg";
        $fullPath = "$directory/$filename";

        // Ensure directory exists
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Save QR code
        File::put($fullPath, $qrCode);


        // Save only the relative path in the database
        $store->sector_qr = $filename;
        $store->save(); // Save the store with the QR code path
    }

    /**
     * Show the form for editing the specified seller.
     */
    public function editSeller(User $seller)
    {

        if (!$seller->store) {
            abort(404, 'Store not found.');
        }

        // Verify the seller belongs to the delegate
        if ($seller->store->delegate_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load the store relationship instead of stores
        $seller->load('store');

        // Get data for dropdowns
        $sellerTypes = SellerType::all();
        $sections = Section::where('is_online', 1)->get();
        $countries = Country::with('cities')->orderBy('name')->get(); // Eager load cities

        return view('delegates.edit-seller', compact('seller', 'sellerTypes', 'sections', 'countries'));
    }

    /**
     * Update the specified seller in storage.
     */
    public function updateSeller(Request $request, User $seller)
    {

        if (!$seller->store) {
            abort(404, 'Store not found.');
        }

        // Verify the seller belongs to the delegate
        if ($seller->store->delegate_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        // Verify the seller belongs to the delegate
        if ($seller->store->status !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'You are not authorized to update this seller data');
        }

        // Validate the request
        $validated = $request->validate([
            'seller_first_name' => 'required|string|max:255',
            'seller_last_name' => 'required|string|max:255',
            'seller_type' => 'required|exists:seller_types,id',
            'email' => 'required|email|unique:users,email,' . $seller->id,
            'phone_number1' => 'required|string|max:255',
            'phone_number2' => 'nullable|string|max:255',

            // Store information
            'store_name' => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
            'licensed_operator_number' => 'nullable|string|max:255',
            'sector_representative' => 'required|string|max:255',
            'location' => 'required|string',
            'work_days' => 'required|array',
            'working_hours_from' => 'required',
            'working_hours_to' => 'required',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'contract_img' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'store_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
        ]);

        // Begin transaction
        DB::beginTransaction();

        try {

            // Update user/seller information
            $seller->update([
                'first_name' => $validated['seller_first_name'],
                'last_name' => $validated['seller_last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone_number1'],
                'phone2' => $validated['phone_number2'],
                'seller_type_id' => $validated['seller_type'],
                'facebook' => $validated['facebook'],
                'instagram' => $validated['instagram'],
                'city' => $validated['city_id'],
                'country' => $validated['country_id'],
            ]);

            // Update store information
            $seller->store->update([
                'name' => $validated['store_name'],
                'section_id' => $validated['section_id'],
                'licensed_operator_number' => $validated['licensed_operator_number'],
                'sector_representative' => $validated['sector_representative'],
                'location' => $validated['location'],
                'work_days' => json_encode($request->work_days),
                'working_hours' => date('h:i A', strtotime($request->working_hours_from)) . ' - ' .
                    date('h:i A', strtotime($request->working_hours_to)),
                'facebook' => $validated['facebook'],
                'instagram' => $validated['instagram'],
                'phone_number1' => $validated['phone_number1'],
                'phone_number2' => $validated['phone_number2'],
                'city' => $validated['city_id'],
                'country' => $validated['country_id'],
            ]);

            // Handle image uploads using the existing method
            $this->handleImageUploads($request, $seller->store);

            DB::commit();

            return redirect()
                ->route('delegates.relatedSellers') // Adjust this route to your sellers listing page
                ->with('success', 'Seller updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'An error occurred while updating the seller. Please try again.');
        }
    }
    /**
     * Request to delete a store by updating its status to delete_requested.
     */
    public function requestStoreDeletion($storeId)
    {
        $store = Store::where('id', $storeId)
            ->where('delegate_id', auth()->id())
            ->firstOrFail();

        if ($store->status === 'delete_requested') {
            return back()->with('info', 'Deletion request already submitted.');
        }

        $store->status = 'delete_requested';
        $store->save();

        return back()->with('success', 'Store deletion request submitted successfully.');
    }
    public function downloadQrPdf($id)
    {
        $store = Store::findOrFail($id); // Find store by ID

        if (!$store->sector_qr) {
            return back()->with('error', 'No QR Code available for this store.');
        }

        // Construct the full file path
        $qrCodePath = public_path('images/qrcodes/' . basename($store->sector_qr));

        if (!file_exists($qrCodePath)) {
            return back()->with('error', 'QR Code file not found.');
        }

        // Convert image to base64 to embed in the PDF
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($qrCodePath));

        // Load the PDF view
        $pdf = Pdf::loadView('store-and-seller.single-qr-pdf', compact('store', 'qrCodeBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("store-qr-{$store->id}.pdf");
    }
}
