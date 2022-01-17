<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\CustomerService;
use App\Services\General\GeneralService;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerUpsertRequest;
use App\Utils\ApplicationUtils;

class CustomerController extends Controller
{
    private $service;
    public function __construct(CustomerService $sr) {
        parent::__construct();
        $this->service = $sr;
    }
    
    public function getDataById(GeneralService $gs, $id=""){
        $this->data['organizations'] = $gs->getClietOrganizations(auth()->id());
        $this->data['services'] = $gs->getClietServices(auth()->id());
        if(isset($id) && $id > 0){
            $this->data['details'] = $this->service->getDetailsById($id);
        }
//        dd($this->data['details']->services->pluck('organization_id')->toArray());
        return view('client.customer.detail', ['data' => $this->data]);
    }
    
    public function upsert(CustomerUpsertRequest $request) {
        $message = "Data Not Saved";
        $result = $this->service->upsertData($request->input('customer'), auth()->id());
        
        if($result){
            $message = "Data Saved Succesfully";
        }
        
        return redirect()->route('customer.list')->with('message', $message);
    }
    
    public function list(){
        return view('client.customer.list');
    }
    
    public function serverList(Request $request): string {
        $result = [];
        
        $return = $this->service->getList($request->input('start'), $request->input('length'), $request->input('search.value'));
        
        if(!empty($return)){
            $result = $this->getPaginationData($return['data']);
        }
        
        $json_data = ApplicationUtils::getPaginationReturnData($request, $return['totalData'], $return['totalFiltered'], $result);
        return json_encode($json_data); 
    }
    
    private function getPaginationData(object $data_arr): array {
        $result = [];
        $i = 0;
        foreach ($data_arr as $data)
            {
                $edit = route('customer.add.edit', [$data->id]);
                
                $nestedData['serial_number'] = ++$i;
                $nestedData['name'] = $data->name;
                $nestedData['mobile'] = $data->mobile;
                $nestedData['dob'] = date('d/m/Y', strtotime($data->dob));
                $nestedData['options'] = "<a class='btn btn-xs btn-default text-primary mx-1 shadow' title='Edit' href='{$edit}' ><i class='fa fa-lg fa-fw fa-pen'></i></a>";
                $result[] = $nestedData;

            }
        return $result;
    }
}
