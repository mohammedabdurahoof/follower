<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\API;

use App\Services\API\APIBaseService;
use App\Traits\GetTableNameTrait;
use App\Models\AdminMaster;
use Illuminate\Support\Facades\Schema;

/**
 * Description of ListService
 *
 * @author Ritobroto
 */
class ListService extends APIBaseService {
    
    use GetTableNameTrait;

    public function getDataImageDocumentList(array $inputs): array {
        $return['services'] = parent::getServiceByOrganization($inputs['organization_id']);
        
        $return['list'] = $this->getDataList($inputs);

        return $return;
    }

    private function getDataList(array $inputs): array {
        $return = [];
        $table_name = $this->getTableName($inputs['service_id'], $inputs['organization_id']);
        $hasTable = \Schema::Connection(env('DB_CONNECTION'))->hasTable($table_name);
        if (isset($inputs['client_id'], $inputs['customer_id']) && $hasTable) {
            $return['data'] = \DB::table($table_name)
                    ->join("clients", "$table_name.client_id", "=", "clients.id")
                    ->join("customers", "$table_name.customer_id", "=", "customers.id")
                    ->select('clients.id as clients_client_id', 'clients.name as client_name', 'customers.name as customer_name', "$table_name.*")
                    ->where("$table_name.client_id", $inputs['client_id'])
                    ->where("$table_name.customer_id", $inputs['customer_id'])
                    ->get();
        }
        if(!$hasTable){
            $return = $this->getImagesDocuments($inputs['service_id'], $inputs['organization_id']);
        }
        return $return;
    }
    
    private function getImagesDocuments(int $service_id, int $organization_id): array {
        $return = [];
        $admin_master = AdminMaster::with('admin_master_image')
                ->where('organization_id', $organization_id)
                ->where('service_id', $service_id)
                ->where('file_type', '<>', 'txt')->get();
        foreach($admin_master as $v){
            $i = 0;
            if(count($v->admin_master_image) > 0 && $v->file_type == "images"){
                foreach($v->admin_master_image as $image){
                    $x = $i++;
                    $return['images'][$x]['name'] = $v->file_name;
                    $return['images'][$x]['link'] = asset($image->image_link);
                }
            }
            if(isset($v->file_link) && $v->file_type == "pdf"){
                $x = $i++;
                $return['docs'][$x]['name'] = $v->file_name;
                $return['docs'][$x]['url'] = asset($v->file_link);
            }
        }
        return $return;
        
    }

}
