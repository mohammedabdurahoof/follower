<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\General\GeneralService;
use App\Services\General\FileuploadService;
use Illuminate\Http\Request;
use App\Http\Requests\Client\DataUploadRequest;
use App\Utils\ApplicationUtils;
use App\Services\Client\DataUploadService;


class ClientDataController extends Controller
{
    private $service, $fs;
    public function __construct(DataUploadService $sr, FileuploadService $fs) {
        parent::__construct();
        $this->service = $sr;
        $this->fs = $fs;
        $this->data['file_type'] = ApplicationUtils::uploadAbleFileType('client');
    }
    
    public function getDataById(GeneralService $gs, $id=""){
        $this->data['organizations'] = $gs->getClietOrganizations(auth()->id());
        $this->data['services'] = $gs->getClietServices(auth()->id());
        
        if(isset($id) && $id > 0){
            $this->data['details'] = $this->service->getDetailsById($id);
            if(isset($this->data['details']->file_type, $this->data['file_type'][$this->data['details']->file_type], $this->data['details']->file_link) && $this->data['details']->file_link !== ""){
                $full_url = config('app.uploaded_assets').$this->data['details']->file_link;
                $this->data['details']->file_view = "<a class='btn btn-lg btn-default text-primary shadow' title='View' href='{$full_url}' target='_blank' >".$this->data['file_type'][$this->data['details']->file_type]."</a>";
            }
        }
//        dd($this->data['details']->toArray());
        return view('client.data.detail', ['data' => $this->data]);
    }
    
    public function upsert(DataUploadRequest $request) {
        $message = "Data Not Saved";
        $result = $this->service->upsertData($request->input('data'), auth()->id());
        if(isset($result->id) && $result->file_type !== "txt" && $request->hasFile('data.file_link')) {
            $message = $this->uploadFiles($request->file(), $result);
        }
        return redirect()->route('client.data.upload.list')->with('message', $message);
    }
    
    
    public function list(){
        return view('client.data.list');
    }
    
    public function serverList(Request $request): string {
        $result = [];
        
        $return = $this->service->getList($request->input('start'), $request->input('length'), auth()->id(), $request->input('search.value'));
        
        if(!empty($return)){
            $result = $this->getPaginationData($return['data']);
        }
        $json_data = ApplicationUtils::getPaginationReturnData($request, $return['totalData'], $return['totalFiltered'], $result);
        return json_encode($json_data); 
    }
    
    private function getPaginationData(object $data_arr): array {
        $result = [];
        (int)$i = 0;
        foreach ($data_arr as $data)
            {
                $edit = route('client.data.upload.add.edit', [$data->id]);
                
                $file_details = $this->getFileIcon($data->file_type, $data->file_link);
                
                $nestedData['serial_number'] = ++$i;
                $nestedData['service_group'] = isset($data->service) ? $data->service->name : "No Service found";
                $nestedData['organization_name'] = isset($data->organization) ? $data->organization->name : "No Organization found";
                $nestedData['file_uploaded_date'] = date('d/m/Y', strtotime($data->created_at));
                $nestedData['file_link'] = !$file_details ? "No File Found" : $file_details;
                $nestedData['options'] = "<a class='btn btn-xs btn-default text-primary mx-1 shadow' title='Edit' href='{$edit}' ><i class='fa fa-lg fa-fw fa-pen'></i></a>";
                $result[] = $nestedData;

            }
        return $result;
    }
    
    private function getFileIcon($file_type, string $link=null): string {
        $return = false;
        
        if(isset($file_type, $this->data['file_type'][$file_type]) && $link !== ""){
            $full_url = config('app.uploaded_assets').$link;
            $return = "<a class='btn btn-xs btn-default text-primary mx-1 shadow' title='View' href='{$full_url}' target='_blank' >".$this->data['file_type'][$file_type]."</a>";
        }
        return $return;
    }
    
    private function uploadFiles(array $inputs, object $client_data_upload) : string {
        $message = "Data uploaded Without File";
        $directory_file = $this->fs->getFilePath('client', 'data_upload', $client_data_upload->id);
//        dd($directory_file);
        // * check has file and send to service
        // 1. delete file
        // 2. upload new file for the admin data
        // 3. Use Upload single file function
        if(isset($inputs['data']['file_link'])) {
            $return = $this->fs->uploadFile($inputs['data']['file_link'], $directory_file, 'ClientData', $client_data_upload->id, 'file_link');
            $message = $return['message'];
            $message .= $this->uploadDataToCustomer($return['model']->file_link, $client_data_upload);
        }
        
        return $message;
    }
    
    private function uploadDataToCustomer(string $file, object $client_data_upload): string {
        $message = ", But Cannot Load Excel data";
        
        if(isset($file, $client_data_upload->id)) {
            $message = $this->service->uploadCustomerData($file, $client_data_upload);
        }
        return $message;
    }
}
