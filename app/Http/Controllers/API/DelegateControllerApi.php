<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\UpdateSellerRequest;
use App\Http\Resources\SellerResource;
use App\Models\SellerType;
use App\Models\Store;
use App\Models\StoreBranch;
use App\Models\User;
use App\Services\SellerTypes\SellerTypeFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;  // Importing the correct Request class
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class DelegateControllerApi extends Controller
{

    /**
     * Get all sellers related to the authenticated delegate.
     *
     * @return JsonResponse
     */
    public function getRelatedSellers(): JsonResponse
    {
        $delegateId = auth()->id(); // Get the authenticated delegate's ID

        // Fetch stores where the delegate_id matches the authenticated delegate
        $stores = Store::where('delegate_id', $delegateId)
            ->with(['branches', 'products']) // Eager load branches and products
            ->get();

        if ($stores->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No related sellers found for this delegate.',
            ], 200);
        }

        // Get seller details for all stores
        $sellers = User::whereIn('id', $stores->pluck('user_id'))->get();

        // Combine the seller and store data and format using SellerResource
        $responseData = $sellers->map(function ($seller) use ($stores) {
            // Find the store related to the seller
            $store = $stores->firstWhere('user_id', $seller->id);

            // Return the data using SellerResource with the seller and store
            return new SellerResource(compact('seller', 'store'));
        });

        return response()->json([
            'status' => true,
            'data' => $responseData,
        ]);
    }

    /**
     * Endpoint to retrieve the details of a seller and their associated store.
     * This method fetches the seller and store data based on the seller ID passed in the route.
     *
     * @param int $sellerId
     * @return JsonResponse
     */
    public function edit(int $sellerId): JsonResponse
    {
        $seller = User::find($sellerId);
        $store = Store::where('user_id', $sellerId)->first();

        if (!$seller || !$store) {
            return response()->json([
                'status' => false,
                'message' => 'Seller or store not found.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => new SellerResource(compact('seller', 'store')),
        ]);
    }
    /**
     * Endpoint to update a seller of a specific type.
     * This method validates the seller ID and type before calling the main updateSeller method.
     *
     * @param UpdateSellerRequest $request
     * @return JsonResponse
     */
    public function update(UpdateSellerRequest $request): JsonResponse
    {
        // Step 1: Validate seller type
        $sellerId = $request->header('sellerId'); // Get the seller type from headers

        // Step 2: Validate if seller exists
        $seller = User::find($sellerId);
        if (!$seller) {
            return response()->json([
                'status' => false,
                'message' => 'Seller not found.',
            ], 404); // Not Found
        }

        // Step 3: Proceed with the update
        return $this->updateSeller($request, $seller);
    }

    /**
     * Endpoint to updateSeller a seller and their associated store.
     * This method updates the seller and store details, handling images,
     * store branches, and seller-specific logic based on the seller type.
     *
     * @param UpdateSellerRequest $request
     * @param int $sellerId
     * @return JsonResponse
     */
    public function updateSeller(UpdateSellerRequest $request, $seller): JsonResponse
    {

        // Find the seller and store by sellerId
        $store = Store::where('user_id', $seller->id)->first();

        if (!$seller || !$store) {
            return response()->json([
                'status' => false,
                'message' => 'Seller or store not found.',
            ], 404);
        }

        DB::beginTransaction(); // Start a transaction

        try {
            // Update Seller (User)
            $seller->update([
                'first_name' => $request->first_name ?? $seller->first_name,
                'last_name' => $request->last_name ?? $seller->last_name,
                'email' => $request->email ?? $seller->email,
                'phone' => $request->phone ?? $seller->phone,
                'phone2' => $request->whatsapp_number ?? $seller->phone2,
                'city' => $request->branches[0]['city'] ?? $seller->city,
                'country' => $request->branches[0]['country'] ?? $seller->country,
            ]);

            // Update Store
            $store->update([
                'name' => $request->store_name ?? $store->name,
                'section_id' => $request->section_id ?? $store->section_id,
                'description' => $request->store_description ?? $store->description,
                'discount_percentage' => $request->discount_percentage ?? $store->discount_percentage,
                'seller_name' => $request->first_name ?? $store->seller_name,
                'city' => $request->branches[0]['city'] ?? $store->city,
                'country' => $request->branches[0]['country'] ?? $store->country,
                'sector_representative' => $request->sector_representative ?? $store->sector_representative,
                'location' => $request->location ?? $store->location,
                'phone_number1' => $request->phone ?? $store->phone_number1,
                'phone_number2' => $request->whatsapp_number ?? $store->phone_number2,
                'email' => $request->email ?? $store->email,
                'work_hours' => $request->work_hours ?? $store->work_hours,
                'work_days' => $request->work_days ? json_encode($request->work_days) : $store->work_days,
                'facebook' => $request->facebook ?? $store->facebook,
                'instagram' => $request->instagram ?? $store->instagram,
                'tiktok' => $request->tiktok ?? $store->tiktok,
            ]);

            // Handle image uploads if new images are provided
            $this->handleImageUploads($request, $store);

            // Handle store branches (update or add new)
            if (!empty($request->branches)) {
                foreach ($request->branches as $branch) {
                    StoreBranch::updateOrCreate(
                        ['store_id' => $store->id, 'city' => $branch['city'], 'country' => $branch['country']],
                        ['status' => true] // Active by default
                    );
                }
            }

            // Apply seller-specific logic using Strategy Pattern
            $strategy = SellerTypeFactory::getStrategy($seller->seller_type_id);
            $strategy->handle($request, $store);

            // Commit transaction
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Seller updated successfully',
                'data' => new SellerResource(compact('seller', 'store')),
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            Log::error('Error updating seller: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the seller. Please try again.',
            ], 500);
        }
    }


    /**
     * Endpoint to create a seller of a specific type.
     * This method handles the creation of a seller with type-specific logic, based on the seller type passed.
     *
     * @param StoreSellerRequest $request
     * @return JsonResponse
     */
    public function store(StoreSellerRequest $request): JsonResponse
    {
        // Step 1: Validate seller type
        $sellerTypeId = $request->header('sellertypeid'); // Get the header value

        if (!$sellerTypeId) {
            return response()->json([
                'status' => false,
                'message' => 'Seller type ID is required in the header.',
            ], 200); // Return 400 Bad Request
        }

        $validSellerTypes = SellerType::pluck('id')->toArray();  // Get all the IDs of valid seller types
        if (!in_array($sellerTypeId, $validSellerTypes)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid seller type. Please provide a valid seller type.',
            ], 200);  // Return a 400 Bad Request response
        }

        // If valid, proceed with the seller creation
        return $this->storeSeller($request, $sellerTypeId);
    }

    /**
     * Common logic to store a seller and their associated store.
     * This method handles creating the user, store, handling images,
     * store branches, and seller-specific logic based on seller type.
     *
     * @param StoreSellerRequest $request
     * @param int $sellerTypeId
     * @return JsonResponse
     */
    private function storeSeller(StoreSellerRequest $request, int $sellerTypeId): JsonResponse
    {

        DB::beginTransaction(); // Start a transaction

        try {
            // 1️⃣ Create the Seller (User)
            $seller = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'phone2' => $request->whatsapp_number,
                'type' => 'seller',
                'seller_type_id' => $sellerTypeId, // Dynamic seller type
                'password' => Hash::make($request->password),
                'city' => $request->branches[0]['city'] ?? null,
                'country' => $request->branches[0]['country'] ?? null,
            ]);

            // 2️⃣ Create the Store
            $store = Store::create([
                'name' => $request->store_name,
                'user_id' => $seller->id,
                'section_id' => $request->section_id,
                'description' => $request->store_description,
                'discount_percentage' => $request->discount_percentage,
                'password' => Hash::make($request->password),
                'seller_name' => $request->first_name,
                'section_id' => $request->section_id,
                'city' => $request->branches[0]['city'] ?? null,
                'country' => $request->branches[0]['country'] ?? null,
                'delegate_id' => auth()->user()->id,
                'sector_representative' => $request->sector_representative,
                'location' => $request->location,
                'phone_number1' => $request->phone,
                'phone_number2' => $request->whatsapp_number,
                'email' => $request->email,
                'work_hours' => $request->work_hours,
                'work_days' => json_encode($request->work_days),
                'status' => "pending",
                'facebook' => $request->facebook ?? null,
                'instagram' => $request->instagram ?? null,
                'tiktok' => $request->tiktok ?? null,
            ]);
            // Generate and save the QR code for the store
            $this->generateStoreQrCode($store);

            // Handle image uploads
            $this->handleImageUploads($request, $store);

            // 3️⃣ Create Store Branches
            if (!empty($request->branches)) {
                foreach ($request->branches as $branch) {
                    StoreBranch::create([
                        'store_id' => $store->id,
                        'city' => $branch['city'],
                        'country' => $branch['country'],
                        'status' => true, // Active by default
                    ]);
                }
            }

            // Handle seller-specific logic using Strategy Pattern
            $strategy = SellerTypeFactory::getStrategy($sellerTypeId);
            $strategy->handle($request, $store);

            // Commit the transaction if everything is successful
            DB::commit();

            // Return Resource Response
            return response()->json([
                'status' => true, // Indicate that the request was successful
                'message' => 'Seller created successfully',
                'data' => new SellerResource(compact('seller', 'store')),
            ], 201); // 201: Created
        } catch (\Exception $e) {
            // Rollback the transaction if there is an error
            DB::rollBack();
            // Delete the seller if created
            if (isset($seller)) {
                $seller->delete();
            }
            // Log the error for debugging purposes (optional)
            Log::error('Error updating seller: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating the seller. Please try again.',
            ], 500); // 500: Internal Server Error
        }
    }

    /**
     * Endpoint to delete a seller and their associated store.
     * This method handles the deletion of a seller and their store based on the seller ID passed.
     * If the seller or store is not found, it returns a 404 error.
     *
     * @param int $sellerId
     * @return JsonResponse
     */
    public function destroy(int $sellerId): JsonResponse
    {
        // Find the seller and store by sellerId
        $seller = User::find($sellerId);
        $store = Store::where('user_id', $sellerId)->first();

        // Check if seller and store exist
        if (!$seller || !$store) {
            return response()->json([
                'status' => false,
                'message' => 'Seller or store not found.',
            ], 200); // 404: Not Found
        }

        // Delete the seller and the store
        $seller->delete();
        $store->delete();

        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Seller and store deleted successfully',
        ]);
    }


    /**
     * Handle the image uploads for the seller/store.
     * This method moves the uploaded images to the designated directories.
     * It works for both storing and updating images.
     *
     * @param Request $request
     * @param Store $store
     * @return void
     */
    private function handleImageUploads(Request $request, Store $store)
    {
        // Handle contract_img upload
        if ($request->hasFile('contract_img')) {
            $image = $request->file('contract_img');
            $imageName = hash('sha256', time() . Str::random(10)) . '.' . $image->getClientOriginalExtension(); // Hashed filename
            $destinationPath = public_path('images/contractImages');

            // Delete the old image if it exists (only for update)
            if ($store->contract_img && file_exists($destinationPath . '/' . $store->contract_img)) {
                unlink($destinationPath . '/' . $store->contract_img);
            }

            // Move the new image
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $image->move($destinationPath, $imageName);
            $store->contract_img = $imageName;
        }

        // Handle store_img upload
        if ($request->hasFile('store_img')) {
            $image = $request->file('store_img');
            $imageName = hash('sha256', time() . Str::random(10)) . '.' . $image->getClientOriginalExtension(); // Hashed filename
            $destinationPath = public_path('images/storeImages');

            // Delete the old image if it exists (only for update)
            if ($store->store_img && file_exists($destinationPath . '/' . $store->store_img)) {
                unlink($destinationPath . '/' . $store->store_img);
            }

            // Move the new image
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $image->move($destinationPath, $imageName);
            $store->store_img = $imageName;
        }

        // Save updated store data
        $store->save();
    }

    /**
     * Generate and save the QR code for the store.
     *
     * @param Store $store
     * @return void
     */
    private function generateStoreQrCode(Store $store): void
    {
        // Generate a unique hashed filename for the QR code
        $hashedFileName = hash('sha256', time() . Str::random(10)) . '.svg';

        // Convert hex color to RGB
        $red = 192;  // C0 in hex
        $green = 26; // 1A in hex
        $blue = 134; // 86 in hex

        // Generate the QR code with custom color
        $qrCode = QrCode::format('svg')
            ->size(300) // Set the size of the QR code image
            ->color($red, $green, $blue) // Apply custom color
            ->generate(route('store.discount', ['store' => $store->id])); // QR content

        // Define the folder path
        $destinationPath = public_path('images/qrcodes');

        // Ensure the directory exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Define the full path to save the QR code image
        $qrCodePath = $destinationPath . '/' . $hashedFileName;

        // Save the QR code image to the specified path
        File::put($qrCodePath, $qrCode);

        // Save only the relative path in the database
        $store->sector_qr = $hashedFileName;
        $store->save(); // Save the store with the QR code path
    }
}
