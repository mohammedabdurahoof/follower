<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class DataUploadRequest extends FormRequest
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
            'data.organization_id' => 'required|integer',
            'data.service_id' => 'required|integer',
            'data.file_type' => 'required|string',
            'data.file_link' => 'mimes:csv,xlsx,xls|max:4100',
        ];
    }
    
    public function messages() {
        return [
            'data.organization_id.required' => 'Organization Name Is Required',
            'data.organization_id.integer' => 'Please select a Valid Organization From List',
            'data.service_id.required' => 'Service Is Required',
            'data.service_id.integer' => 'Please select a Valid service From List',
            'data.file_type.required' => 'File Type Is Required',
            'data.file_type.string' => 'Select A Valid File Type',
            'data.file_link.mimes' => 'Allowed File Types Are XLSX/XLS/CSV',
            'data.file_link.max' => 'Maximum Allowed File Size 4 MB',
        ];
    }
}
