<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use App\Services\API\OrgclientListService;
use App\Http\Requests\API\CustomerHomeRequest;
use App\Http\Requests\API\ClientsServicesRequest;

class OrgClientListController extends ApiController
{
    private $cs;
    
    public function __construct(OrgclientListService $cs) {
        parent::__construct();
        $this->cs = $cs;
    }
    
    public function getCustomerOrganization(CustomerHomeRequest $request) {
        $req = $request->validated();
        $customer = $this->cs->getCustomerOrganization($req['id']);
        if(!isset($customer->id)) {
            return $this->sendError("Customer Not Found", ["Invalid Customer", "Server Data Not Found"]);
        }
        return $this->sendResponse($customer);
    }
    
    public function getClientsServices(ClientsServicesRequest $request){
        $req = $request->validated();
        $data = $this->cs->getClientsServices($req);
        if(!isset($data['services'], $data['clients'])) {
            return $this->sendError("Data Not Found", ["Invalid Client Data", "Server Data Not Found"]);
        }
        return $this->sendResponse($data);
    }
}
