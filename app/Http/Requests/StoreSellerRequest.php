<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreSellerRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15|unique:users,phone',
            'whatsapp_number' => 'nullable|string|max:15',

            'password' => 'required|string|confirmed',

            'contract_img' => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf',
            'store_img' => 'nullable|mimes:jpg,png,jpeg,gif,svg',

            'store_name' => 'required',
            'section_id' => 'required|exists:sections,id',

            'sector_representative' => 'required',
            'work_hours' => 'required',
            'work_days' => 'required|array|min:1',

            'branches' => 'nullable|array|min:1',
        ];

        switch ((int) $this->header('sellertypeid')) {
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


}
