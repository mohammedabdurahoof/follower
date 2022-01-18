<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\API;

use App\Services\API\APIBaseService;
use App\Models\Customer;

/**
 * Description of OrgclientListService
 *
 * @author Ritobroto
 */
class OrgclientListService extends APIBaseService {

    public function getCustomerOrganization(int $id): Customer {
        $customer = Customer::with('organizations')->find($id);
        foreach($customer->organizations as $data){
            $data->logo = asset($data->logo);
        }
        return $customer;
    }

    public function getClientsServices(array $req): array {
        $return['services'] = parent::getServiceByOrganization($req['organization_id']);

        $clients = \DB::table('customer_organization')
                        ->join('clients', 'customer_organization.client_id', '=', 'clients.id')
                        ->select('customer_organization.customer_id', 'clients.id', 'clients.name', 'clients.mobile', 'clients.email', 'clients.logo')
                        ->where('customer_organization.organization_id', $req['organization_id'])
                        ->where('customer_organization.customer_id', $req['customer_id'])->get();
        
        foreach($clients as $data) {
            $data->logo = asset($data->logo);
        }
        $return['clients'] = $clients;

        return $return;
    }

}
