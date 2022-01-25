<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\API\AttachmentdetailRequest;
use App\Http\Requests\API\DatadetailRequest;
use App\Services\API\DetailService;

class DetailContoller extends ApiController
{
    private $service;
    
    public function __construct(DetailService $ds) {
        parent::__construct();
        $this->service = $ds;
    }
    
    public function getDataDetails(DatadetailRequest $request) {
        $req = $request->validated();
        $data = $this->service->getDataDetails($req);
        if(!isset($data['services'], $data['data'])){
            return $this->sendError("Data Not Found", ["Invalid Data Input", "Server Data Not Found"]);
        }
//        dd($data);
        return $this->sendResponse($data);
    }
    
    public function getImageDetails(AttachmentdetailRequest $request) {
        $req = $request->validated();
        $data = $this->service->getImageDetails($req);
        if(!isset($data['services'], $data['data'])){
            return $this->sendError("Image Not Found", ["Invalid Image Data", "Image Not Found"]);
        }
//        dd($data);
        return $this->sendResponse($data);
    }
    
    public function getDocumentDetails(AttachmentdetailRequest $request) {
        $req = $request->validated();
        $data = $this->service->getDocumentDetails($req);
        if(!isset($data['services'], $data['data'])){
            return $this->sendError("Document Not Found", ["Invalid Document Data", "Document Not Found"]);
        }
//        dd($data);
        return $this->sendResponse($data);
    }
}
