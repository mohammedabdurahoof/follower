<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\API;

use App\Models\Service;

/**
 * Description of APIBaseService
 *
 * @author Ritobroto
 */
class APIBaseService {

    public function getServiceByOrganization(int $organization_id) {
        return Service::where('organization_id', $organization_id)
                        ->where('status', 1)->get(['id', 'organization_id', 'name']);
    }

}
