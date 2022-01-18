<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ClientService;
use App\Services\General\GeneralService;
use App\Services\General\FileuploadService;
use App\Utils\ApplicationUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    private $service, $fs;
    
    public function __construct(ClientService $sr, FileuploadService $fs) {
        parent::__construct();
        $this->service = $sr;
        $this->fs = $fs;
        $this->data['status'] = ApplicationUtils::getStatus();
    }
    
    public function getDataById(GeneralService $gs, $id=""){
        $this->data['organizations'] = $gs->getOrganizations();
        $this->data['services'] = $gs->getServicesForClient();
        if(isset($id) && $id > 0){
            $this->data['details'] = $this->service->getDetailsById($id);
        }
//        dd($this->data['details']->services->pluck('organization_id')->toArray());
        return view('admin.clients.detail', ['data' => $this->data]);
    }
    
    public function upsert(Request $request) {
        $inputs = $request->only('client')['client'];
//        dd($request->file());
        $validator = $this->validator($inputs, $inputs['id']);
        $message = "Data Not Saved";
        if ($validator->passes()) {
            $result = $this->service->upsertData($inputs);
            if(isset($result->id)){
                $message = "Data Saved, No New Logo Has Been Uploaded";
            }
            if(isset($result->id) && ($request->hasFile('client.logo') || $request->hasFile('client.bottom_image'))) {
                $message = $this->uploadFiles($request->file(), $result->id);
            }
            return redirect()->route('client.list')->with('message', $message);
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }
    
    public function delete(int $id) {
        $return = $this->service->delete($id);
        if(isset($return->id)){
            return redirect()->route('client.list')->with('message', $return->name . " Deleted With Inventory From Server");
        }
        return redirect()->route('client.list')->with('error', 'Could not Delete Data!!');
    }
    
    public function list(){
        return view('admin.clients.list', $this->data);
    }
    public function serverList(Request $request): string {
        $result = [];
        
        $clients = $this->service->getList($request->input('start'), $request->input('length'), $request->input('search.value'));
        
        if(!empty($clients)){
            $result = $this->getPaginationData($clients['data']);
        }
        $json_data = ApplicationUtils::getPaginationReturnData($request, $clients['totalData'], $clients['totalFiltered'], $result);
        return json_encode($json_data); 
    }
    
    protected function validator(array $data, $id="") {
        $logo = 'mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:2048';
        $btm_img = 'mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:2048|dimensions:min_width=510,min_height=180,max_width=960';
        if($id === "") {
            $logo .= '|required';
        }
        if($id === "") {
            $btm_img .= '|required';
        }
        $validator = [
            'name' => 'string|max:100|required', 'services' => 'required',
            'status' => 'required', 'organization_id' => 'required',
            'place' => 'required|string|max:100', 'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:clients,mobile,'.$id,
            'email' => 'required|email', 'dob' => 'required|date',
            'logo' => $logo, 'bottom_image' => $btm_img
        ];
        return Validator::make($data, $validator);
    }
    
    private function getPaginationData(object $clients): array {
        $result = [];
        foreach ($clients as $client)
            {
                $edit = route('client.add.edit', [$client->id]);
                $delete = route('client.delete', [$client->id]);

                $nestedData['name'] = ucwords($client->name);
                $nestedData['dob'] = date('d/m/Y', strtotime($client->dob));
                $nestedData['org'] = isset($client->organization) ? $client->organization->name : "No Organization found";
                $nestedData['dom'] = date('d/m/Y', strtotime($client->dom));
                $nestedData['mobile'] = $client->mobile;
                $nestedData['email'] = $client->email;
                $nestedData['place'] = ucwords($client->place);
                $nestedData['cadre'] = ucwords($client->cadre);
                $nestedData['product'] = ucwords($client->product);
                $nestedData['pdate'] = date('d/m/Y', strtotime($client->p_date));
                $nestedData['rdate'] = date('d/m/Y', strtotime($client->r_date));
                $nestedData['status'] = ucfirst($this->data['status'][$client->status]);
                $nestedData['options'] = "<a class='btn btn-xs btn-default text-primary mx-1 shadow' title='Edit' href='{$edit}' ><i class='fa fa-lg fa-fw fa-pen'></i></a> ";
                $nestedData['options'] .= "<a class='btn btn-xs btn-danger text-error mx-1 shadow' title='Delete' href='{$delete}' ><i class='fa fa-lg fa-fw fa-trash'></i></a>";
                $result[] = $nestedData;

            }
        return $result;
    }
    
    private function uploadFiles(array $inputs, int $id) : string {
        $message = "Data uploaded Without File";
        $directory_file = $this->fs->getFilePath('admin', 'client', $id);
//        dd($directory_file);
        // * check has file and send to service
        // 1. delete file
        // 2. upload new file for the admin data
        // 3. Use Upload single file function
        if(isset($inputs['client']['logo'])) {
            $return = $this->fs->uploadFile($inputs['client']['logo'], $directory_file, 'Client', $id, 'logo');
            $message = $return['message'];
        }
        
        if(isset($inputs['client']['bottom_image'])) {
            $return = $this->fs->uploadFile($inputs['client']['bottom_image'], $directory_file, 'Client', $id, 'bottom_image');
            $message = $return['message'];
        }
        
        return $message;
    }
}
