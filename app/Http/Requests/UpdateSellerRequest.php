<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSellerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($this->getSellerIdFromHeader()),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:15',
                Rule::unique('users', 'phone')->ignore($this->getSellerIdFromHeader()),
            ],
            'whatsapp_number' => 'nullable|string|max:15',

            'password' => 'nullable|string|confirmed',

            'contract_img' => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf',
            'store_img' => 'nullable|mimes:jpg,png,jpeg,gif,svg',

            'store_name' => 'required',
            'section_id' => 'nullable|exists:sections,id',

            'sector_representative' => 'required',
            'work_hours' => 'required',
            'work_days' => 'required|array|min:1',

            'branches' => 'nullable|array|min:1',
        ];

        switch ($this->getSellerTypeId()) {
            case 1:
                $rules['discount_percentage'] = 'required|numeric|min:0|max:100';
                break;
            case 2:
                $rules['discount_percentage'] = 'required|numeric|min:0|max:100';
                $rules['excluded_products'] = 'required|array|min:1';
                $rules['products.*.name'] = 'required|string';
                break;
            case 3:
                $rules['products'] = 'required|array|min:1';
                $rules['products.*.name'] = 'required|string';
                $rules['products.*.price_before_discount'] = 'required|numeric';
                $rules['products.*.discount_percentage'] = 'nullable|numeric|min:0|max:100';
                $rules['products.*.discount_amount'] = 'nullable|numeric|min:0';
                break;
        }

        return $rules;
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Get the seller ID from the header, fallback to auth if not present
            $sellerId = $this->getSellerIdFromHeader();

            // Debugging the sellerId (remove or comment out dd() after testing)
            // dd($sellerId);

            if (!$sellerId) {
                $validator->errors()->add('sellerId', 'Seller ID is required in the header.');
                return;
            }

            // Check if the seller exists and has the "seller" role
            $sellerExists = User::where('id', $sellerId)->where('type', 'seller')->exists();

            if (!$sellerExists) {
                $validator->errors()->add('sellerId', 'Invalid seller ID. The user is not a seller.');
            }
        });
    }

    /**
     * Get Seller ID from Header or use the authenticated user ID
     */
    private function getSellerIdFromHeader()
    {
        $sellerId = $this->header('sellerId') ?? auth()->user()->id;
        // Try to get the sellerId from the request header, if not found fallback to authenticated user ID
        return (int) $sellerId;
    }

    /**
     * Get Seller Type ID from Users Table based on the seller ID
     */
    private function getSellerTypeId()
    {
        // Fetch the seller type ID for the seller
        return User::where('id', $this->getSellerIdFromHeader())->value('seller_type_id');
    }
}
