<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\AwardService;
use App\Services\General\FileuploadService;
use Illuminate\Http\Request;
use App\Utils\ApplicationUtils;
use App\Http\Requests\AwardUpsertRequest;

class AwardController extends Controller
{
    private $service, $fs;
    public function __construct(AwardService $sr, FileuploadService $fs) {
        parent::__construct();
        $this->service = $sr;
        $this->fs = $fs;
    }
    
    public function getDataById($id=""){
        if(isset($id) && $id > 0){
            $this->data['details'] = $this->service->getDetailsById($id);
        }
//        dd($this->data['details']->toArray());
        return view('client.awards.detail', ['data' => $this->data]);
    }
    public function upsert(AwardUpsertRequest $request) {
        $message = "Data Not Saved";
        $result = $this->service->upsertData($request->input('award'));
        if(isset($result->id) && $request->hasFile('award.photo')) {
            $message = $this->uploadFiles($request->file(), $result->id);
        }
        return redirect()->route('award.list')->with('message', $message);
    }
    public function list(){
        return view('client.awards.list');
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
        foreach ($data_arr as $data)
            {
                $edit = route('award.add.edit', [$data->id]);

                $nestedData['award_name'] = $data->name;
                $nestedData['award_from'] = $data->received_from;
                $nestedData['received_date'] = date('d/m/Y', strtotime($data->achieved_date));
                $nestedData['options'] = "<a class='btn btn-xs btn-default text-primary mx-1 shadow' title='Edit' href='{$edit}' ><i class='fa fa-lg fa-fw fa-pen'></i></a>";
                $result[] = $nestedData;

            }
        return $result;
    }
    
    private function uploadFiles(array $inputs, int $id) : string {
        $message = "Data uploaded Without File";
        $directory_file = $this->fs->getFilePath('client', 'award', $id);
//        dd($directory_file);
        // * check has file and send to service
        // 1. delete file
        // 2. upload new file for the admin data
        // 3. Use Upload single file function
        if(isset($inputs['award']['photo'])) {
            $return = $this->fs->uploadFile($inputs['award']['photo'], $directory_file, 'ClientAward', $id, 'photo');
            $message = $return['message'];
        }
        
        return $message;
    }
}
