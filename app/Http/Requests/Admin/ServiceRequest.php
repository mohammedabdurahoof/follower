<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'service.name' => 'string|max:100|required|unique:services,name,' . $this->service['id'] . ',id,organization_id,' . $this->service['organization_id'],
            'service.status' => 'required', 'service.organization_id' => 'required',
            'service.fields' => 'array', 
            'admin_master.*.file_name' => 'max:100',
            'admin_master.*.page_top.*' => 'mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:2048',
            'admin_master.*.full_page.*' => 'mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:2048|dimensions:min_width=510,min_height=180,max_width=955',
            'admin_master.*.slide_ad_area.*' => 'mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:2048',
        ];
    }
    
    public function messages() {
        return [
            'service.name.string' => 'Service Name Must Be a Valid String',
            'service.name.required' => 'Service Name is Required',
            'service.name.unique' => 'Service Name Must Be Unique From the selected Organization',
            'service.status.required' => 'At Least Select 1 Status',
            'service.organization_id.required' => 'At Least Select 1 Organization',
            'admin_master.*.page_top.*.mimes' => 'Only jpg,jpeg,png,JPG,JPEG,PNG Allowed for Page Top',
            'admin_master.*.page_top.*.max' => 'Page Top Image Must Be Within 2 MB',
            'admin_master.*.page_bottom.*.mimes' => 'Only jpg,jpeg,png,JPG,JPEG,PNG Allowed for Page bottom',
            'admin_master.*.page_bottom.*.max' => 'Page Bottom Image Must Be Within 2 MB',
            'admin_master.*.full_page.*.mimes' => 'Only jpg,jpeg,png,JPG,JPEG,PNG Allowed for Full Page',
            'admin_master.*.full_page.*.max' => 'Full Page Image Must Be Within 2 MB',
            'admin_master.*.full_page.*.dimensions' => 'Full Page Image Must Be at least(180px X 510 px), And Max Width = 950px',
            'admin_master.*.slide_ad_area.*.mimes' => 'Only jpg,jpeg,png,JPG,JPEG,PNG Allowed for Slide Ad Area',
            'admin_master.*.slide_ad_area.*.max' => 'Slide Ad Area Image Must Be Within 2 MB',
            
        ];
    }

}
