<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CustomerHomeRequest extends FormRequest
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
            'id' => 'required|integer|min:0',
        ];
    }
    
    public function messages() {
        return [
            'id.required' => 'Customer Is Required',
            'id.integer' => 'Please Select Valid Customer',
            'id.min' => 'No Customer Found',
        ];
    }
}
