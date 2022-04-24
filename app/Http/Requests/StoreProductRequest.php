<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            "title"=>"required|min:3",
            "description"=>"required|min:20",
            'brand_id'=>'nullable',
            'category_id'=>'nullable',
            "photo"=>"nullable|file|mimes:jpeg,png|max:2000"
        ];
    }
}
