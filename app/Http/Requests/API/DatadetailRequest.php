<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class DatadetailRequest extends FormRequest
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
            'id' => 'required|numeric|min:0',
            'organization_id' => 'required|numeric|min:0',
            'service_id' => 'required|numeric|min:0',
            'customer_id' => 'required|numeric|min:0',
        ];
    }
    
    public function messages() {
        return [
            'id.required' => 'Data Is Required',
            'id.integer' => 'Please Select Valid Data',
            'id.min' => 'No Data Found',
            'organization_id.required' => 'Organization Is Required',
            'organization_id.integer' => 'Please Select Valid Organization',
            'organization_id.min' => 'No Organization Found',
            'service_id.required' => 'Service Is Required',
            'service_id.integer' => 'Please Select Valid Service',
            'service_id.min' => 'No Service Found',
            'customer_id.required' => 'Customer Is Required',
            'customer_id.integer' => 'Please Select Valid Customer',
            'customer_id.min' => 'No Customer Found',
        ];
    }
}
