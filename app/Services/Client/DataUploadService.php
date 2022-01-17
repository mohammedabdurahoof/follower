<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Client;

use App\Models\ClientData;
use App\Models\CustomerNotification;
use App\Imports\CustomerImport;

/**
 * Description of DataUploadService
 *
 * @author Ritobroto
 */
class DataUploadService {
    public function getList(int $start, int $limit, int $user, $query=""): array {
        $return['totalData'] = ClientData::count();
        $return['totalFiltered'] = $return['totalData'];
        
        $qry = ClientData::with(['organization', 'service']);
        if($query != ""){ 
            $search = strtolower($query); 
            
            $qry->whereRaw("LOWER(name) LIKE '%{$search}%'");
            $qry->orWhereHas('organization', function($query) use ($search) {
                $query->whereRaw("LOWER(name) LIKE '%{$search}%'");
            });
            $qry->orWhereHas('service', function($query) use ($search) {
                $query->whereRaw("LOWER(name) LIKE '%{$search}%'");
            });
            
            $return['totalFiltered'] = $qry->count();
        }
        $return['data'] = 
                $qry->where('client_id', $user)
                ->offset($start)->limit($limit)->orderBy('id','DESC')->get();
//        dd($return);
        return $return;
    }
    
    public function getDetailsById(int $id): ClientData {
        return ClientData::with(['service', 'organization', 'client', 'client_data_images', 'customer_notification'])
                ->find($id);
    }
    
    public function upsertData(array $inputs, int $client_id): ClientData {
//        dd($inputs);
        if($inputs['id'] == ""){
            $clientData = new ClientData();
        }
        if($inputs['id'] > 0){
            $clientData = ClientData::where('id', $inputs['id'])->firstOrfail();
        }
        foreach($inputs as $k => $v){
            if($k == 'id' ||  $k == "notification"){
                continue;
            }
            $clientData->$k = $v;
        }
        $clientData->client_id = $client_id;
        $clientData->save();
        if(isset($inputs['notification']) && $inputs['notification'] != ""){
            $this->updateNotification($inputs['notification'], $clientData->id);
        }
        
        return $clientData;
    }
    
    public function uploadCustomerData(string $file, ClientData $data): string {
        $message = ". Server Error!!";
        $table_name = $this->getTableName($data->service_id, $data->organization_id);
        $return = (new CustomerImport($data, $table_name))->import($file);
        if($return){
            $message = ". Check Customer List Page Or Try Login Using Mobile APP!!";
        }
        
        return $message;
    }
    
    private function updateNotification(string $value, int $clientDataId) {
        CustomerNotification::updateOrCreate(
                ['client_data_id' => $clientDataId],
                [
                    'notification' => $value
                ]
        );
    }
    
    private function getTableName(int $serviceId, int $orgId): string {
        $org = \App\Models\Organization::findOrFail($orgId);
        $service = \App\Models\Service::findOrFail($serviceId);
        $tablenameToLower = \Str::lower($org->name . ' ' . $service->name);
        return \Str::slug($tablenameToLower, '_');
    }
}
