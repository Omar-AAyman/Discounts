<?php

namespace App\Http\Controllers\API\Sellers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSellerRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SellerResource;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreBranch;
use App\Models\User;
use App\Services\SellerTypes\SellerTypeFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class SellerController extends Controller
{
    /**
     * Endpoint to get the authenticated seller's data.
     * This method fetches the seller's information along with the associated store data.
     *
     * @return JsonResponse
     */
    public function getSellerData(): JsonResponse
    {
        // Step 1: Get the authenticated seller (user)
        $seller = auth()->user();

        // Step 2: Validate if seller exists
        if (!$seller) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your account.',
            ], 200); // Not Found
        }

        // Step 3: Fetch the store related to the seller
        $store = Store::where('user_id', $seller->id)->first();

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your store.',
            ], 200); // Not Found
        }

        // Step 4: Return the seller and store data using the SellerResource
        return response()->json([
            'status' => true,
            'data' => new SellerResource(compact('seller', 'store')),
        ]);
    }

    /**
     * Get the products of the authenticated seller.
     * This method fetches the products that belong to the authenticated seller's store.
     *
     * @return JsonResponse
     */
    public function getSellerProducts(): JsonResponse
    {
        // Step 1: Get the authenticated seller (user)
        $seller = auth()->user();

        // Step 2: Validate if seller exists
        if (!$seller) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your account.',
            ], 200); // Not Found
        }

        // Step 3: Fetch the store related to the seller
        $store = Store::where('user_id', $seller->id)->first();

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your store.',
            ], 200); // Not Found
        }

        // Get products related to the store
        $products = $store->products;

        // Return the products in the response
        return response()->json([
            'status' => true,
            'message' => 'Products retrieved successfully.',
            'data' => ProductResource::collection($products),
        ]);
    }

    /**
     * Update a specific product for the authenticated seller.
     * This method allows the seller to update product details by its ID.
     *
     * @param Request $request
     * @param int $productId
     * @return JsonResponse
     */
    public function updateProduct(Request $request, $productId): JsonResponse
    {
        // Step 1: Get the authenticated seller (user)
        $seller = auth()->user();

        // Step 2: Validate if seller exists
        if (!$seller) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your account.',
            ], 200); // Not Found
        }

        // Step 3: Fetch the store related to the seller
        $store = Store::where('user_id', $seller->id)->first();

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your store.',
            ], 200); // Not Found
        }

        // Step 4: Get the product by its ID
        $product = $store->products()->find($productId);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 200); // Not Found
        }

        // Step 5: Validate and update product details
        $product->update([
            'name' => $request->name ?? $product->name,
            'price' => $request->price_before_discount ?? $product->price,
            'discount_percentage' => $request->discount_percentage ?? $product->discount_percentage,
            'discount_amount' => $request->discount_amount ?? $product->discount_amount,
        ]);

        // Handle image uploads if new images are provided
        $this->handleProductImageUploads($request, $product);

        // Step 6: Return the updated product
        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully.',
            'data' => new ProductResource($product), // Return updated product using resource
        ]);
    }
    /**
     * Delete a specific product for the authenticated seller.
     * This method allows the seller to delete a product by its ID.
     *
     * @param int $productId
     * @return JsonResponse
     */
    public function deleteProduct($productId): JsonResponse
    {
        // Step 1: Get the authenticated seller (user)
        $seller = auth()->user();

        // Step 2: Validate if seller exists
        if (!$seller) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your account.',
            ], 200);
        }

        // Step 3: Fetch the store related to the seller
        $store = Store::where('user_id', $seller->id)->first();

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your store.',
            ], 200);
        }

        // Step 4: Get the product by its ID
        $product = $store->products()->find($productId);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 200);
        }


        // Step 6: Delete product images if any exist
        $this->deleteProductImages($product);

        // Step 7: Delete the product
        $product->delete();

        // Step 8: Return success response
        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }

    /**
     * Handle product image deletion.
     *
     * @param Product $product
     */
    private function deleteProductImages(Product $product)
    {
        $imagePath = public_path('images/productImages/' . $product->img);

        // Check if the image exists before deleting
        if ($product->img && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }



    /**
     * Endpoint to update your (seller's) data.
     * This method validates your data before calling the updateSeller method.
     *
     * @param UpdateSellerRequest $request
     * @return JsonResponse
     */
    public function update(UpdateSellerRequest $request): JsonResponse
    {
        // Step 1: Get the authenticated seller (user)
        $seller = auth()->user();

        // Step 2: Validate if seller exists
        if (!$seller) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your account.',
            ], 404); // Not Found
        }

        // Step 3: Proceed with the update
        return $this->updateSeller($request, $seller);
    }

    /**
     * Endpoint to update your seller account and associated store details.
     * This method updates your personal details and store information, handling images,
     * store branches, and applying seller-specific logic based on your seller type.
     *
     * @param UpdateSellerRequest $request
     * @param User $seller
     * @return JsonResponse
     */
    private function updateSeller(UpdateSellerRequest $request, $seller): JsonResponse
    {
        // Find your store by seller's ID
        $store = Store::where('user_id', $seller->id)->first();

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your store.',
            ], 404); // Not Found
        }

        DB::beginTransaction(); // Start a transaction

        try {
            // Update your (seller's) personal details
            $seller->update([
                'first_name' => $request->first_name ?? $seller->first_name,
                'last_name' => $request->last_name ?? $seller->last_name,
                'email' => $request->email ?? $seller->email,
                'phone' => $request->phone ?? $seller->phone,
                'phone2' => $request->whatsapp_number ?? $seller->phone2,
                'city' => $request->branches[0]['city'] ?? $seller->city,
                'country' => $request->branches[0]['country'] ?? $seller->country,
            ]);

            // Update your store details
            $store->update([
                'name' => $request->store_name ?? $store->name,
                'section_id' => $request->section_id ?? $store->section_id,
                'description' => $request->store_description ?? $store->description,
                'discount_percentage' => $request->discount_percentage ?? $store->discount_percentage,
                'seller_name' => $request->first_name ?? $store->seller_name,
                'city' => $request->branches[0]['city'] ?? $store->city,
                'country' => $request->branches[0]['country'] ?? $store->country,
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
            $this->handleStoreImageUploads($request, $store);

            // Handle store branches (add new and then remove old)
            if (!empty($request->branches)) {
                $newBranchIds = []; // Array to hold new branch IDs
                foreach ($request->branches as $branch) {
                    if (isset($branch['id'])) {
                        // Update existing branch if an ID is provided
                        $existingBranch = StoreBranch::find($branch['id']);
                        if ($existingBranch) {
                            $existingBranch->city = $branch['city'] ?? $existingBranch->city;
                            $existingBranch->country = $branch['country'] ?? $existingBranch->country;
                            $existingBranch->save();
                            $newBranchIds[] = $existingBranch->id; // Add to new branch IDs
                        }
                    } else {
                        // Create a new branch if no ID is provided
                        $newBranch = StoreBranch::create([
                            'store_id' => $store->id,
                            'city' => $branch['city'],
                            'country' => $branch['country'],
                            'status' => true // Active by default
                        ]);
                        $newBranchIds[] = $newBranch->id; // Add to new branch IDs
                    }
                }

                // Remove old branches that are not in the new list
                StoreBranch::where('store_id', $store->id)
                    ->whereNotIn('id', $newBranchIds)
                    ->delete(); // Remove old branches
            }

            // // Apply seller-specific logic using Strategy Pattern
            // $strategy = SellerTypeFactory::getStrategy($seller->seller_type_id);
            // $strategy->handle($request, $store);

            // Commit transaction
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Your account and store have been updated successfully!',
                'data' => new SellerResource(compact('seller', 'store')),
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            dd('Error updating seller: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating your account. Please try again later.',
            ], 500);
        }
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
    private function handleStoreImageUploads(Request $request, Store $store)
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
     * Handle the image uploads for the product.
     * This method moves the uploaded images to the designated directories.
     * It works for both storing and updating images.
     *
     * @param Request $request
     * @param Product $product
     * @return void
     */
    private function handleProductImageUploads(Request $request, Product $product)
    {
        // Handle contract_img upload
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = hash('sha256', time() . Str::random(10)) . '.' . $image->getClientOriginalExtension(); // Hashed filename
            $destinationPath = public_path('images/productImages');

            // Delete the old image if it exists (only for update)
            if ($product->img && file_exists($destinationPath . '/' . $product->img)) {
                unlink($destinationPath . '/' . $product->img);
            }

            // Move the new image
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $image->move($destinationPath, $imageName);
            $product->img = $imageName;
        }


        // Save updated product data
        $product->save();
    }

    /**
     * Get all excluded products for the authenticated seller.
     * This method retrieves the products that are excluded from the seller's store.
     *
     * @return JsonResponse
     */
    public function getExcludedProducts(): JsonResponse
    {
        // Step 1: Get the authenticated seller (user)
        $seller = auth()->user();

        // Step 2: Validate if seller exists
        if (!$seller) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your account.',
            ], 200); // Not Found
        }

        // Step 3: Fetch the store related to the seller
        $store = Store::where('user_id', $seller->id)->first();

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your store.',
            ], 200); // Not Found
        }

        // Get excluded products related to the store
        $excludedProducts = $store->excludedProducts; // Assuming a relationship exists

        // Return the excluded products in the response
        return response()->json([
            'status' => true,
            'message' => 'Excluded products retrieved successfully.',
            'data' => ProductResource::collection($excludedProducts),
        ]);
    }

    /**
     * Update the list of excluded products for the authenticated seller.
     * This method allows the seller to add or remove products from the excluded list.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateExcludedProducts(Request $request): JsonResponse
    {
        $request->validate([
            'excluded_products' => 'required|array|min:1',
            'excluded_products.*.name' => 'required|string',
            'excluded_products.*.id' => 'nullable|integer|exists:products,id' // Validate existing product IDs
        ]);

        // Step 1: Get the authenticated seller (user)
        $seller = auth()->user();

        // Step 2: Validate if seller exists
        if (!$seller) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your account.',
            ], 404); // Not Found
        }

        // Step 3: Fetch the store related to the seller
        $store = Store::where('user_id', $seller->id)->first();

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your store.',
            ], 404); // Not Found
        }

        // Step 4: Process excluded products
        DB::transaction(function () use ($store, $request) {
            // Step 4a: Collect existing product IDs
            $existingProductIds = $store->excludedProducts()->pluck('id')->toArray();

            // Step 4b: Prepare new excluded product IDs
            $newExcludedProductIds = [];

            foreach ($request->excluded_products as $product) {
                // Check if the product has an ID
                if (isset($product['id'])) {
                    // If it has an ID, update the product name if a new name is provided
                    if (isset($product['name'])) {
                        $existingProduct = Product::find($product['id']);
                        $existingProduct->name = $product['name'];
                        $existingProduct->save();
                    }
                    // Add it to the new list
                    $newExcludedProductIds[] = $product['id'];
                } else {
                    // If it doesn't have an ID, create a new product
                    $newProduct = Product::create(['name' => $product['name'], 'is_excluded_from_discount' => 1, 'store_id' => $store->id]);
                    $newExcludedProductIds[] = $newProduct->id; // Add the new product ID to the list
                }
            }

            // Step 4c: Remove old excluded products that are not in the new list
            foreach ($existingProductIds as $existingId) {
                if (!in_array($existingId, $newExcludedProductIds)) {
                    // Delete the product if it's not in the new list
                    $store->excludedProducts()->where('id', $existingId)->delete();
                }
            }
        });

        // Fetch the updated list of excluded products
        $updatedExcludedProducts = $store->excludedProducts;

        return response()->json([
            'status' => true,
            'message' => 'Excluded products updated successfully.',
            'data' => ProductResource::collection($updatedExcludedProducts), // Return updated excluded products

        ]);
    }
}
