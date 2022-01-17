<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Client;

use App\Models\Client;
use App\Models\ClientOrganizationDetail;

/**
 * Description of DashboradService
 *
 * @author Ritobroto
 */
class DashboradService {
    public function getData(int $authId): Client {
        return Client::with(['organization', 'services', 'client_organization_detail'])
                ->where('id', $authId)->first();
    }
    
    public function addDetails(array $inputs) : bool {
        if($inputs['id'] == ""){
            $client_details = new ClientOrganizationDetail();
        }
        if($inputs['id'] > 0){
            $client_details = ClientOrganizationDetail::where('id', $inputs['id'])->firstOrfail();
        }
        foreach($inputs as $k => $v){
            if($k == 'id'){
                continue;
            }
            $client_details->$k = $v;
        }
        return $client_details->save();
    }
}
