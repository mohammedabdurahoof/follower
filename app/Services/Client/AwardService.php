<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Client;
use App\Models\ClientAward;

/**
 * Description of FollowerserviceClientAward
 *
 * @author Ritobroto
 */
class AwardService {
    public function getList(int $start, int $limit, int $user, $query=""): array {
        $return['totalData'] = ClientAward::count();
        $return['totalFiltered'] = $return['totalData'];
        
        $qry = ClientAward::query();
        if($query != ""){ 
            $search = strtolower($query); 
            
            $qry->whereRaw("LOWER(name) LIKE '%{$search}%'");
            
            $return['totalFiltered'] = $qry->count();
        }
        $return['data'] = 
                $qry->where('client_id', $user)
                ->offset($start)->limit($limit)->orderBy('id','DESC')->get();
//        dd($return);
        return $return;
    }
    
    public function getDetailsById(int $id): ClientAward {
        return ClientAward::find($id);
    }
    
    public function upsertData(array $inputs): ClientAward {
        if($inputs['id'] == ""){
            $award = new ClientAward();
        }
        if($inputs['id'] > 0){
            $award = ClientAward::where('id', $inputs['id'])->firstOrfail();
        }
        foreach($inputs as $k => $v){
            if($k == 'id'){
                continue;
            }
            $award->$k = $v;
        }
        $award->save();
        return $award;
    }
    
    public function delete(int $id): bool {
        return ClientAward::destroy($id);
    }
}
