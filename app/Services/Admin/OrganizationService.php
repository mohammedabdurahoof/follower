<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Admin;

use App\Contracts\Admin\OrganizationContract;
use App\Models\Organization;

/**
 * Description of OrganizationService
 *
 * @author Ritobroto
 */
class OrganizationService implements OrganizationContract {
    public function getList(int $start, int $limit, $query=""): array {
        $return['totalData'] = Organization::count();
        $return['totalFiltered'] = $return['totalData'];
        
        $qry = Organization::query();
        if($query != ""){ 
            $search = strtolower($query); 
            
            $qry->whereRaw("LOWER(name) LIKE '%{$search}%'");
            
            $return['totalFiltered'] = $qry->count();
        }
        $return['data'] = $qry->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        return $return;
    }
    
    public function orgDetailsById(int $id): Organization {
        return Organization::findOrFail($id);
    }
    
    public function upsertData(array $inputs): Organization {
        if($inputs['id'] == ""){
            $organization = new Organization();
        }
        if($inputs['id'] > 0){
            $organization = Organization::where('id', $inputs['id'])->firstOrfail();
        }
        foreach($inputs as $k => $v){
            if($k == 'id'){
                continue;
            }
            $organization->$k = $v;
        }
        $organization->save();
        return $organization;
    }
}
