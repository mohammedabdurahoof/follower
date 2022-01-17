<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientdashboardRequest extends FormRequest
{
    
    /**
     * The route to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirectRoute = 'client.dashboard';
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
            'client_details.address' => 'required|string|max:150',
            'client_details.about' => 'required|string|max:250',
        ];
    }
    
    public function messages() {
        return [
            'client_details.address.required' => 'Address Field Is Required',
            'client_details.address.max' => 'Address Must be within 150 characters',
            'client_details.about.required' => 'About Us Field Is Required',
            'client_details.about.max' => 'About Us Must be within 250 characters',
        ];
    }
}
