<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ClientsServicesRequest extends FormRequest
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
            'customer_id' => 'required|integer|min:0',
            'organization_id' => 'required|integer|min:0',
        ];
    }
    
    public function messages() {
        return [
            'customer_id.required' => 'Customer Is Required',
            'customer_id.integer' => 'Please Select Valid Customer',
            'customer_id.min' => 'No Customer Found',
            'organization_id.required' => 'Organization Is Required',
            'organization_id.integer' => 'Please Select Valid Organization',
            'organization_id.min' => 'No Organization Found',
        ];
    }
}
