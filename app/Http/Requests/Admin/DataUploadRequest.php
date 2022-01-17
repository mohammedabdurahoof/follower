<?php

namespace App\Http\Requests\Admin;

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
            'data.file_name' => 'required|string|max:100',
            'data.notification' => 'string',
            'data.file_link' => 'mimes:csv,xlsx,xls,pdf,docx,txt|max:5500',
            'data.images.*' => 'mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:2048',
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
            'data.file_name.required' => 'File Name Is Required',
            'data.file_name.string' => 'Enter A Valid File Name',
            'data.file_name.max' => 'Max 100 Characters Allowed For File Name',
            'data.service_id.integer' => 'Please select a Valid File Type From List',
            'data.notification.string' => 'Please Enter a Valid String In Notification',
            'data.file_link.mimes' => 'Allowed File Types Are XLS/XLS/PDF/CSV',
            'data.file_link.max' => 'Maximum Allowed File Size 5.5 MB',
            'data.images.*.mimes' => 'Allowed Images Are jpg/jpeg/png',
            'data.images.*.max' => 'Maximum Allowed Image Size 2 MB',
        ];
    }
}
