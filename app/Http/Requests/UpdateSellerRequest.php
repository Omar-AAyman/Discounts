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
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->getSellerIdFromHeader()),
            ],
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users', 'phone')->ignore($this->getSellerIdFromHeader()),
            ],
            'whatsapp_number' => 'nullable|string|max:15',

            'password' => 'nullable|string|confirmed',

            'contract_img' => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf',
            'store_img' => 'nullable|mimes:jpg,png,jpeg,gif,svg',

            'store_name' => 'required',
            'section_id' => 'required|exists:sections,id',

            'sector_representative' => 'required',
            'work_hours' => 'required',
            'work_days' => 'required|array|min:1',

            'branches' => 'nullable|array|min:1',
        ];

        switch ($this->getSellerTypeId()) {
            case 1:
                $rules['products'] = 'required|array|min:1';
                $rules['products.*.name'] = 'required|string';
                $rules['products.*.price_before_discount'] = 'required|numeric';
                $rules['products.*.discount_percentage'] = 'nullable|numeric|min:0|max:100';
                $rules['products.*.discount_amount'] = 'nullable|numeric|min:0';
                break;
            case 2:
                $rules['discount_percentage'] = 'required|numeric|min:0|max:100';
                break;
            case 3:
                $rules['discount_percentage'] = 'required|numeric|min:0|max:100';
                $rules['excluded_products'] = 'required|array|min:1';
                $rules['products.*.name'] = 'required|string';
                break;
        }

        return $rules;
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $sellerId = $this->getSellerIdFromHeader();

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
     * Get Seller ID from Header
     */
    private function getSellerIdFromHeader()
    {
        return (int) $this->header('sellerId');
    }

    /**
     * Get Seller Type ID from Users Table
     */
    private function getSellerTypeId()
    {
        return User::where('id', $this->getSellerIdFromHeader())->value('seller_type_id');
    }
}
