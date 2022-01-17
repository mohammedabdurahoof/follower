<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\DashboradService;
use App\Services\General\GeneralService;
use App\Http\Requests\ClientdashboardRequest;
use App\Utils\ApplicationUtils;

class DashboardController extends Controller
{
    private $service;
    public function __construct(DashboradService $sr) {
        parent::__construct();
        $this->service = $sr;
        $this->data['status'] = ApplicationUtils::getStatus();
    }
    public function dashboard(GeneralService $gs) {
        $this->data['services'] = $gs->getServices();
        $this->data['details'] = $this->service->getData(auth()->id());
        return view('client.dashboard', ['data' => $this->data]);
    }
    public function saveDetails(ClientdashboardRequest $request) {
        $message = "Data Not Saved";
        $result = $this->service->addDetails($request->input('client_details'));
        if($result){
            $message = "Data Saved Succesfully";
        }
        return redirect()->route('client.dashboard')->with('message', $message);
    }
}
