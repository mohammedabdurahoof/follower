<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Client;

use App\Models\Customer;
use Hash;

/**
 * Description of ClientClient
 *
 * @author Ritobroto
 */
class CustomerService {
    public function getList(int $start, int $limit, $query=""): array {
        $qury = Customer::withWhereHas('services', function ($q) {
            $q->where('client_id', auth()->id());
        })->withWhereHas('organizations', function ($q) {
            $q->where('client_id', auth()->id());
        });
        $qry = $qury;
        $return['totalData'] = $qury->count();
        $return['totalFiltered'] = $return['totalData'];
        
        if($query != ""){ 
            $search = strtolower($query); 
            $qry->whereRaw("LOWER(name) LIKE '%{$search}%'");
            $qry->orWhereRaw("LOWER(mobile) LIKE '%{$search}%'");
            
            $return['totalFiltered'] = $qry->count();
        }
        $return['data'] = $qry->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        return $return;
    }
    
    public function getDetailsById(int $id): Customer {
        return Customer::with(['services','organizations.client'])
                ->find($id);
    }
    
    public function upsertData(array $inputs, $authId): bool{
//        dd($inputs);
        if($inputs['id'] == ""){
            $customer = new Customer();
        }
        if($inputs['id'] > 0){
            $customer = Customer::where('id', $inputs['id'])->firstOrfail();
        }
        foreach($inputs as $k => $v){
            if($k == 'id' || $k == 'services' || $k == 'organizations'){
                continue;
            }
            $customer->$k = $v;
        }
        $password_input = date('dmY', strtotime($inputs['dob']));
        $customer->password = Hash::make($password_input);
        $customer->status = 1;
        $updated_inserted_row = $customer->save();
        $this->updatedRelations($inputs['services'], $inputs['organizations'], $authId, $customer);
        return $updated_inserted_row;
    }
    
    private function updatedRelations(array $services, array $organizations, $clientId, Customer $customer){
        $sync_org = $sync_service = [];
        foreach($organizations as $org) {
            $sync_org[$org] = ['client_id' => $clientId];
        }
        foreach($services as $service) {
            $sync_service[$service] = ['client_id' => $clientId];
        }
        $customer->organizations()->wherePivot('client_id', $clientId)->sync($sync_org);
        return $customer->services()->wherePivot('client_id', $clientId)->sync($sync_service);
    }
}
