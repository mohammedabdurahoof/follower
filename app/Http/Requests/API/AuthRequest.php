<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password' => 'required|string',
        ];
    }
    
    public function messages() {
        return [
            'mobile.required' => 'Mobile Number Is Required',
            'mobile.regex' => 'Enter A Valid Mobile',
            'mobile.min' => 'Mobile Must Be 10 Digit',
            'password.required' => 'Password Is Required',
        ];
    }
}
