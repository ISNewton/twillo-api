<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type'           => 'required|string|in:rent,sale',
            'name'           => 'required|string|max:50',
            'bedrooms'       => 'required|integer|max:100',
            'bathrooms'      => 'required|integer|max:100',
            'parking'        => 'required|boolean',
            'furnished'      => 'required|boolean',
            'address'        => 'required|string|max:100',
            'offer'          => 'required|boolean',
            'regularPrice'   => 'required|numeric|max:99999999',
            'discountedPrice'   => 'nullable|numeric|max:99999999',
            'images'         => 'nullable',
            'images.*'       => 'mimes:jpg,jpeg,png,webp|max:4000',
        ];
    }
}
