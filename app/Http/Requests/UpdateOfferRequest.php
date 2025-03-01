<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UpdateOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'discount_percentage' => 'nullable|numeric|min:0|max:100', // Ensure it's a percentage
            'discount_amount' => 'nullable|numeric|min:0', // Price for each product
            'price_before_discount' => 'nullable|numeric|min:0', // Price for each product
            'name' => 'required|string|max:255', // Name for each product
            'bg_img' => 'required|mimes:jpg,png,jpeg,gif,svg',
        ];
    }
}
