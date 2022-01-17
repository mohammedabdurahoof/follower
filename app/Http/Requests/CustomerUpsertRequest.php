<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpsertRequest extends FormRequest
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
            'customer.name' => 'required|string|max:150',
            'customer.mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:customers,mobile,'.request()->customer['id'],
            'customer.organization.*' => 'required',
            'customer.services.*' => 'required',
            'customer.dob' => 'required|date|before:'.date('Y-m-d'),
        ];
    }
    
    public function messages() {
        return [
            'customer.mobile.unique' => 'Mobile Number Already Exist, in Customer DataBasee',
            'customer.name.string' => 'Customer Name Must Be a Valid String',
            'customer.name.required' => 'Customer Name is Required',
            'customer.organization.*.required' => 'At Least Select 1 Organization',
            'customer.services.*.required' => 'At Least Select 1 Service',
            'customer.dob.before' => 'DOB Must Have a Date Less Than Today',
        ];
    }
}
