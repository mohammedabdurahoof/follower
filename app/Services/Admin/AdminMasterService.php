<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Admin;

use App\Models\AdminMaster;
use App\Models\Notification;


/**
 * Description of AdminMasterService
 *
 * @author Ritobroto
 */
class AdminMasterService {
    public function getList(int $start, int $limit, $query=""): array {
        $return['totalData'] = AdminMaster::count();
        $return['totalFiltered'] = $return['totalData'];
        
        $qry = AdminMaster::with(['organization', 'service']);
        if($query != ""){ 
            $search = strtolower($query); 
            
            $qry->whereHas('organization', function($query) use ($search) {
                $query->whereRaw("LOWER(name) LIKE '%{$search}%'");
            });
            $qry->orWhereHas('service', function($query) use ($search) {
                $query->whereRaw("LOWER(name) LIKE '%{$search}%'");
            });
            
            $return['totalFiltered'] = $qry->count();
        }
        $return['data'] = $qry->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        return $return;
    }
    
    public function getDetailsById(int $id): AdminMaster {
        return AdminMaster::with(['service','organization', 'admin_master_image', 'notification'])
                ->find($id);
    }
    
    public function upsertData(array $inputs): AdminMaster {
//        dd($inputs);
        if($inputs['id'] == ""){
            $adminMaster = new AdminMaster();
        }
        if($inputs['id'] > 0){
            $adminMaster = AdminMaster::where('id', $inputs['id'])->firstOrfail();
        }
        foreach($inputs as $k => $v){
            if($k == 'id' ||  $k == "notification"){
                continue;
            }
            $adminMaster->$k = $v;
        }
        $adminMaster->save();
        if(isset($inputs['notification']) && $inputs['notification'] != ""){
            $this->updateNotification($inputs['notification'], $adminMaster->id);
        }
        return $adminMaster;
    }
    
    private function updateNotification(string $value, int $adminId) {
        Notification::updateOrCreate(
                ['admin_master_id' => $adminId],
                [
                    'notification' => $value
                ]
        );
    }
}
