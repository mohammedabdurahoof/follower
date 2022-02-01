<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\API;

use App\Services\API\APIBaseService;
use App\Traits\GetTableNameTrait;
use App\Utils\ApplicationUtils;
use App\Models\AdminMaster;
use App\Models\AdminMasterImage;

/**
 * Description of DetailService
 *
 * @author Ritobroto
 */
class DetailService extends APIBaseService {

    use GetTableNameTrait;

    private $exceptColumns;

    public function __construct() {
        $this->exceptColumns = ApplicationUtils::getExceptColumns();
    }

    public function getDataDetails(array $inputs): array {
        $return['services'] = parent::getServiceByOrganization($inputs['organization_id']);

        $table_name = $this->getTableName($inputs['service_id'], $inputs['organization_id']);
        $hasTable = \Schema::Connection(env('DB_CONNECTION'))->hasTable($table_name);
        if (isset($inputs['customer_id']) && $hasTable) {
            $result = \DB::table($table_name)
                    ->join("clients", "$table_name.client_id", "=", "clients.id")
                    ->join("customers", "$table_name.customer_id", "=", "customers.id")
                    ->select('clients.id as clients_id', 'clients.name as client_name', 'clients.logo as client_logo', 'clients.mobile as client_mobile', 'clients.email as client_email',
                         "$table_name.*")
                    ->where("$table_name.customer_id", $inputs['customer_id'])
                    ->where("$table_name.id", $inputs['id'])
                    ->first();
            foreach ($this->exceptColumns as $column) {
                if (isset($result->$column)) {
                    unset($result->$column);
                }
            }
            if (isset($result)) {
                $result->client_logo = asset($result->client_logo);
            }
            $return['data'] = $result;
        }

        return $return;
    }

    public function getImageDetails(array $inputs): array {
        $return['services'] = parent::getServiceByOrganization($inputs['organization_id']);
        $admin_master = AdminMasterImage::find($inputs['id']);
        $customer_orgs = \DB::table('customer_organization')
                ->join('clients', 'customer_organization.client_id', '=', 'clients.id')
                ->select('clients.id', 'clients.name', 'clients.logo', 'customer_organization.*')
                ->where('customer_organization.organization_id', $inputs['organization_id'])
                ->orderby('customer_organization.client_id', 'desc')
                ->limit(1)
                ->first();
        dd($admin_master);

        dd($customer_orgs);
        // get merged image
        // Follow Image Controller Merged Iamge Logic
        return $return;
    }

    public function getDocumentDetails(array $inputs): array {
        $return['services'] = parent::getServiceByOrganization($inputs['organization_id']);
        $result = AdminMaster::where('organization_id', $inputs['organization_id'])
                ->where('service_id', $inputs['service_id'])
                ->where('id', $inputs['id'])
                ->first();
        if (isset($result->id)) {
            $result->url = asset($result->file_link);
            unset($result->file_link);
            $return['data'] = $result;
        }
        return $return;
    }

}
