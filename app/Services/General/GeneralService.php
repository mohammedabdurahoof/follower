<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\General;

/**
 * Description of GeneralService
 *
 * @author Ritobroto
 */
class GeneralService {
    public function getOrganizations() {
        return \App\Models\Organization::where('status', 1)->get();
    }
    public function getServices() {
        return \App\Models\Service::where('status', 1)->get();
    }
    public function getServicesForClient() {
        return \App\Models\Service::where('status', 1)->where('table_exists', 'yes')->get();
    }
    public function getServicesForAdminData() {
        return \App\Models\Service::where('status', 1)->where('table_exists', NULL)->get();
    }
    
    public function getClietOrganizations($client) {
        return \App\Models\Organization::whereHas("client", function ($q) use ($client){
            $q->where('id', $client);
        })->where('status', 1)->get(['id', 'name']);
    }
    
    public function getClietServices($client) {
        return \App\Models\Service::whereHas("clients", function ($q) use ($client){
            $q->where('id', $client);
        })->where('status', 1)->where('table_exists', 'yes')->get(['id', 'name']);
    }
}
