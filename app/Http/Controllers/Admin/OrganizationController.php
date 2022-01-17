<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Contracts\Admin\OrganizationContract;
use App\Services\General\FileuploadService;
use Illuminate\Http\Request;
use App\Utils\ApplicationUtils;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    
    private $service, $fs;
    public function __construct(OrganizationContract $crs, FileuploadService $fs) {
        parent::__construct();
        $this->service = $crs;
        $this->fs = $fs;
        $this->data['organization_status'] = ApplicationUtils::getStatus();
    }
    
    public function getOrgById($id=""){
        if(isset($id) && $id > 0){
            $this->data['organization_detail'] = $this->service->orgDetailsById($id);
        }
        return view('admin.organization.detail', ['data' => $this->data]);
    }
    
    public function upsertOrg(Request $request) {
        $inputs = $request->only('organization')['organization'];
        
        $validator = $this->validator($inputs, $inputs['id']);
        $message = "Data Not Saved";
        if ($validator->passes()) {
            $result = $this->service->upsertData($request->input('organization'));
            if(isset($result->id)){
                $message = "Organization Saved, No New Logo Has Been Uploaded";
            }
            if(isset($result->id) && $request->hasFile('organization.logo')) {
                $message = $this->uploadFiles($request->file(), $result->id);
            }
            return redirect()->route('org.list')->with('message', $message);
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }
    
    public function orgList(){
        return view('admin.organization.list', $this->data);
    }
    public function serverList(Request $request): string {
        $result = [];
        
        $organizations = $this->service->getList($request->input('start'), $request->input('length'), $request->input('search.value'));
        
        if(!empty($organizations)){
            $result = $this->getPaginationData($organizations['data']);
        }
        $json_data = ApplicationUtils::getPaginationReturnData($request, $organizations['totalData'], $organizations['totalFiltered'], $result);
        return json_encode($json_data); 
    }
    
    protected function validator(array $data, $id="") {
        $logo = 'required|mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:2048';
        if($id !== "") {
            $logo = 'mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:2048';
        }
        $validator = [
            'name' => 'string|max:100|required|unique:organizations,name,'.$id,
            'status' => 'required', 'logo' => $logo
        ];
        return Validator::make($data, $validator);
    }
    
    private function getPaginationData(object $organizations): array {
        $result = [];
        foreach ($organizations as $organization)
            {
                $edit = route('org.add.edit', [$organization->id]);
                
                $nestedData['org_logo'] = isset($organization->logo) ? $this->getImage($organization->logo, $organization->id) : "Please Upload A Logo";
                $nestedData['org_name'] = isset($organization->name) ? $organization->name : "No Organization found";
                $nestedData['org_status'] = ucfirst($this->data['organization_status'][$organization->status]);
//                $nestedData['org_logo'] = $organization->logo;
                $nestedData['options'] = "<a class='btn btn-xs btn-default text-primary mx-1 shadow' title='Edit' href='{$edit}' ><i class='fa fa-lg fa-fw fa-pen'></i></a>";
                $result[] = $nestedData;

            }
        return $result;
    }
    
    private function getImage(string $logo, int $id): string {
        return '<img id="image-'.$id.'" src="'.config('app.uploaded_assets').$logo.'" width="40px">';
    }
    
    private function uploadFiles(array $inputs, int $id) : string {
        $message = "Data uploaded Without File";
        $directory_file = $this->fs->getFilePath('admin', 'organization', $id);
//        dd($directory_file);
        // * check has file and send to service
        // 1. delete file
        // 2. upload new file for the admin data
        // 3. Use Upload single file function
        if(isset($inputs['organization']['logo'])) {
            $return = $this->fs->uploadFile($inputs['organization']['logo'], $directory_file, 'Organization', $id, 'logo');
            $message = $return['message'];
        }
        
        return $message;
    }
}
