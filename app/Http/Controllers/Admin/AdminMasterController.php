<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminMasterService;
use App\Services\General\GeneralService;
use App\Services\General\FileuploadService;
use App\Utils\ApplicationUtils;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\DataUploadRequest;

class AdminMasterController extends Controller
{
    private $service, $fs;
    
    public function __construct(AdminMasterService $sr, FileuploadService $fs) {
        parent::__construct();
        $this->service = $sr;
        $this->fs = $fs;
        $this->data['user_display_details'] = ApplicationUtils::adminUserDetails();
        $this->data['file_type'] = ApplicationUtils::uploadAbleFileType('admin');
    }
    
    public function getDataById(GeneralService $gs, $id=""){
        $this->data['organizations'] = $gs->getOrganizations();
        $this->data['services'] = $gs->getServices();
        
        if(isset($id) && $id > 0){
            $this->data['details'] = $this->service->getDetailsById($id);
            if(isset($this->data['details']->file_type, $this->data['file_type'][$this->data['details']->file_type], $this->data['details']->file_link) && $this->data['details']->file_link !== ""){
                $full_url = config('app.uploaded_assets').$this->data['details']->file_link;
                $this->data['details']->file_view = "<a class='btn btn-lg btn-default text-primary shadow' title='View' href='{$full_url}' target='_blank' >".$this->data['file_type'][$this->data['details']->file_type]."</a>";
            }
        }
//        dd($this->data['details']->toArray());
        return view('admin.admin_master.detail', ['data' => $this->data]);
    }
    
    public function upsert(DataUploadRequest $request) {
        $message = "Data Not Saved";
        $result = $this->service->upsertData($request->input('data'));
        if(isset($result->id) && $result->file_type !== "txt" && ($request->hasFile('data.images') || $request->hasFile('data.file_link'))) {
            $message = $this->uploadFiles($request->file(), $result->id);
        }
        return redirect()->route('admin.master.list')->with('message', $message);
    }
    
    public function list(){
        return view('admin.admin_master.list', $this->data);
    }
    
    public function serverList(Request $request): string {
        $result = [];
        
        $admin_master = $this->service->getList($request->input('start'), $request->input('length'), $request->input('search.value'));
        
        if(!empty($admin_master)){
            $result = $this->getPaginationData($admin_master['data']);
        }
        $json_data = ApplicationUtils::getPaginationReturnData($request, $admin_master['totalData'], $admin_master['totalFiltered'], $result);
        return json_encode($json_data); 
    }
    
    public function deleteImage(Request $req): string {
        $input = $req->input('image_id');
        return $this->fs->deleteImage('AdminMasterImage', $input, 'image_link');
    }
    
    private function getPaginationData(object $admin_masters): array {
        $result = [];
        (int)$i = 0;
        foreach ($admin_masters as $admin_master)
            {
                $edit = route('admin.master.add.edit', [$admin_master->id]);
                
                $file_details = $this->getFileIcon($admin_master->file_type, $admin_master->file_link);
                
                $nestedData['serial_number'] = ++$i;
                $nestedData['service_group'] = isset($admin_master->service) ? $admin_master->service->name : "No Service found";
                $nestedData['organization_name'] = isset($admin_master->organization) ? $admin_master->organization->name : "No Organization found";
                $nestedData['file_uploaded_date'] = date('d/m/Y', strtotime($admin_master->created_at));
                $nestedData['display_type'] = isset($admin_master->user_display_type, $this->data['user_display_details'][$admin_master->user_display_type]) ? $this->data['user_display_details'][$admin_master->user_display_type] : "File Display Type Not found";
                $nestedData['file_link'] = !$file_details ? "File Type Not Found" : $file_details;
                $nestedData['file_name'] = isset($admin_master->file_name) ? $admin_master->file_name : "No Name Added";
                $nestedData['options'] = "<a class='btn btn-xs btn-default text-primary mx-1 shadow' title='Edit' href='{$edit}' ><i class='fa fa-lg fa-fw fa-pen'></i></a>";
                $result[] = $nestedData;

            }
        return $result;
    }
    
    private function getFileIcon($file_type, string $link=null): string {
        $return = false;
        
        if(isset($file_type, $this->data['file_type'][$file_type]) && $link !== ""){
            $full_url = config('app.uploaded_assets').$link;
            $return = "<a class='btn btn-sm btn-default text-primary mx-1 shadow' title='View' href='{$full_url}' target='_blank' >".$this->data['file_type'][$file_type]."</a>";
        }
        return $return;
    }
    
    private function uploadFiles(array $inputs, int $admin_master_id) : string {
        $message = "Data uploaded Without File";

        // * check has file and send to service
        // 1. delete file
        // 2. upload new file for the admin data
        // 3. Use Upload single file function
        if(isset($inputs['data']['file_link'])) {
            $directory_file = $this->fs->getFilePath('admin', 'admin_master', $admin_master_id);
            //dd($directory_file);
            $return = $this->fs->uploadFile($inputs['data']['file_link'], $directory_file, 'AdminMaster', $admin_master_id, 'file_link');
            $message = $return['message'];
        }
        
        if(isset($inputs['data']['images'])) {
            $directory_images = $this->fs->getFilePath('admin', 'admin_master', $admin_master_id, 'images');
            // * check has file and send to service
            // 1. upload images for the admin data
            // 2. Use Multiple file function
            $return = $this->fs->uploadMultipleFiles($inputs['data']['images'], $directory_images, 'AdminMasterImage', $admin_master_id, 'admin_master_id', 'image_link');
            $message = $return;
        }
        
        return $message;
    }
}
