<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use App\Services\API\ListService;
use App\Http\Requests\API\DataListRequest;

class DataListController extends ApiController
{
    private $service;
    
    public function __construct(ListService $ls) {
        parent::__construct();
        $this->service = $ls;
    }
    
    public function getListWithService(DataListRequest $request){
        $req = $request->validated();
        $data = $this->service->getDataImageDocumentList($req);
        if(!isset($data['services'], $data['list'])){
            return $this->sendError("Data Not Found", ["Invalid Data List", "Server Data Not Found"]);
        }
//        dd($data);
        return $this->sendResponse($data);
    }
    
}
