<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\API;

use App\Models\Customer;
use App\Models\Service;

/**
 * Description of OrgclientListService
 *
 * @author Ritobroto
 */
class OrgclientListService {

    public function getCustomerOrganization(int $id): Customer {
        return Customer::with('organizations')->find($id);
    }

    public function getClientsServices(array $req): array {
        $return['services'] = Service::where('organization_id', $req['organization_id'])
                        ->where('status', 1)->get(['id', 'organization_id', 'name']);
        $return['clients'] = \DB::table('customer_organization')
                        ->join('clients', 'customer_organization.client_id', '=', 'clients.id')
                        ->select('customer_organization.customer_id', 'clients.id', 'clients.name', 'clients.mobile', 'clients.email', 'clients.logo')
                        ->where('customer_organization.organization_id', $req['organization_id'])
                        ->where('customer_id', $req['customer_id'])->get();

        return $return;
    }

}
