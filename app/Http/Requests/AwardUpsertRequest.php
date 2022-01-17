<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AwardUpsertRequest extends FormRequest
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
            'award.name' => 'required|string|max:150',
            'award.received_from' => 'required|string|max:150',
            'award.achieved_date' => 'required|date',
            'award.photo' => 'mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:2048',
        ];
    }
    
    public function messages() {
        return [
            'award.name.required' => 'Award Name Is Required',
            'award.name.max' => 'Award Name Must be within 150 characters',
            'award.received_from.required' => 'Award From Is Required',
            'award.received_from.max' => 'Award From must be within 150 Characters',
            'award.achieved_date.required' => 'Received Date Is Required',
            'award.achieved_date.date' => 'Received Date Must Be a Valid Date',
        ];
    }
}
