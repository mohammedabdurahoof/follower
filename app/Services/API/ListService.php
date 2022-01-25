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
use App\Models\UploadDataSetting;

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
        $return = ["data_type" => "data", "unique" => "", "data" => []];
        $table_name = $this->getTableName($inputs['service_id'], $inputs['organization_id']);
        $hasTable = \Schema::Connection(env('DB_CONNECTION'))->hasTable($table_name);
        if (isset($inputs['customer_id']) && $hasTable) {
            $return['unique'] = $this->getUniqueColumnforData($inputs['service_id']);
            $return['data'] = \DB::table($table_name)
                    ->join("clients", "$table_name.client_id", "=", "clients.id")
                    ->join("customers", "$table_name.customer_id", "=", "customers.id")
                    ->select('clients.id as clients_id', 'clients.name as client_name', 'customers.name as customer_name', "$table_name.*")
                    ->where("$table_name.customer_id", $inputs['customer_id'])
                    ->get();
        }
        if (!$hasTable) {
            $return = $this->getImagesDocuments($inputs['service_id'], $inputs['organization_id'], $return);
        }
        return $return;
    }

    private function getUniqueColumnforData(int $service_id): string {
        $settings = UploadDataSetting::where('service_id', $service_id)->first();
        if ($settings->id) {
            return array_search("unique", $settings->columns);
        }
        return "";
    }

    private function getImagesDocuments(int $service_id, int $organization_id, array $return): array {
        $admin_master = AdminMaster::with('admin_master_image')
                        ->where('organization_id', $organization_id)
                        ->where('service_id', $service_id)
                        ->where('file_type', '<>', 'txt')->get();
        
        $returnData = $this->imagesDocsFilter($return, $admin_master);
        
        return $returnData;
    }

    private function imagesDocsFilter(array $return, object $admin_master): array {
        $i = 0;
        foreach ($admin_master as $v) {
            if (count($v->admin_master_image) > 0 && $v->file_type == "images") {
                $return['data_type'] = "images";
                foreach ($v->admin_master_image as $image) {
                    $return['data'][$i]['id'] = $image->id;
                    $return['data'][$i]['name'] = $v->file_name;
                    $return['data'][$i]['url'] = asset($image->image_link);
                    $i++;
                }
            }
            if (isset($v->file_link) && $v->file_type == "pdf") {
                $return['data_type'] = "docs";
                $return['data'][$i]['id'] = $v->id;
                $return['data'][$i]['name'] = $v->file_name;
                $return['data'][$i]['url'] = asset($v->file_link);
                $i++;
            }
        }
        return $return;
    }

}
