<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\FollowerserviceService;
use App\Services\Admin\AdminMasterService;
use App\Services\Admin\CreatetableForCustomerServiceService;
use App\Services\General\GeneralService;
use App\Services\General\FileuploadService;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ServiceRequest;
use App\Utils\ApplicationUtils;
use Illuminate\Support\Str;

class ServiceController extends Controller {

    private $service, $fs, $createTableS;

    public function __construct(FollowerserviceService $fs, FileuploadService $fus, CreatetableForCustomerServiceService $cts) {
        parent::__construct();
        $this->service = $fs;
        $this->fs = $fus;
        $this->createTableS = $cts;

        $this->data['status'] = ApplicationUtils::getStatus();
        $this->data['exceptcolumns'] = ApplicationUtils::getExceptColumns();
    }

    public function getDataById(GeneralService $gs, $id = "") {
        $this->data['organizations'] = $gs->getOrganizations();
        if ($id == "") {
            $user_display_type = ApplicationUtils::adminUserDetails();
            foreach ($user_display_type as $k => $v) {
                $this->data['user_display'][$k]['slug'] = Str::slug($v, '_');
                $this->data['user_display'][$k]['name'] = $v;
            }
        }
        if ($id > 0) {
            $this->data['service_detail'] = $this->service->getDetailsById($id);
        }
//        dd($this->data['service_detail']->toArray());
        return view('admin.service.detail', ['data' => $this->data]);
    }

    public function list() {
        return view('admin.service.list', $this->data);
    }

    public function serverList(Request $request): string {
        $result = [];

        $service = $this->service->getList($request->input('start'), $request->input('length'), $request->input('search.value'));

        if (!empty($service)) {
            $result = $this->getPaginationData($service['data']);
        }
        $json_data = ApplicationUtils::getPaginationReturnData($request, $service['totalData'], $service['totalFiltered'], $result);
        return json_encode($json_data);
    }

    public function upsertService(ServiceRequest $request, AdminMasterService $ams) {
        $message = null;
        \DB::beginTransaction();
        try {
            $service = $this->service->upsertData($request->input('service'));
            if ($request->has('service.fields')) {
                $columnDataType = $this->service->saveDataTypes($request->input('service')['fields'], $service->id);
            }
            
            if ($request->has('admin_master')) {
                $message = $this->getAdminMasterInsertData($request->input('admin_master'), $service, $request, $ams);
            }
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }
        if (isset($service, $columnDataType) && $request->has('service.table_exists')) {

            $data = $this->createAlterTable($service->id, );
            if ($data != null) {
                $message .= " . Table With Columns Created And Updated!";
            }
        }
        return redirect()->route('service.list')->with('message', "Data Saved " . (isset($message) ? $message : '') . ", To Check Master Data, Visit Admin Master Page!!");
    }

    private function getPaginationData(object $services): array {
        $result = [];
        foreach ($services as $service) {
            $edit = route('service.add.edit', [$service->id]);

            $nestedData['service_name'] = isset($service->name) ? $service->name : "No Service found";
            $nestedData['organization_name'] = isset($service->organization) ? $service->organization->name : "No Organization found";
            $nestedData['service_status'] = ucfirst($this->data['status'][$service->status]);
            $nestedData['options'] = "<a class='btn btn-xs btn-default text-primary mx-1 shadow' title='Edit' href='{$edit}' ><i class='fa fa-lg fa-fw fa-pen'></i></a>";
            $result[] = $nestedData;
        }
        return $result;
    }

    private function getAdminMasterInsertData(array $input, object $service, ServiceRequest $request, $ams): string {
        $message = "Admin Master Not Uploaded";
        foreach ($input as $k => $val) {
            $val['id'] = "";
            $val['service_id'] = $service->id;
            $val['organization_id'] = $service->organization_id;
            $admin_master = isset($val['file_name']) ? $ams->upsertData($val) : null;
            if (isset($admin_master->id) && ($request->hasFile("admin_master." . $k . ".page_top") || $request->hasFile('admin_master.' . $k . '.full_page') || $request->hasFile('admin_master.' . $k . '.slide_ad_area'))) {
                $message = $this->uploadFiles($request->file("admin_master." . $k), $admin_master->id);
            }
        }
        return $message;
    }

    private function uploadFiles(array $inputs, int $admin_master_id): string {

        $message = "";
        $directory_images = $this->fs->getFilePath('admin', 'admin_master', $admin_master_id, 'images');
        if (isset($inputs['page_top'])) {
            $message .= $this->fs->uploadMultipleFiles($inputs['page_top'], $directory_images, 'AdminMasterImage', $admin_master_id, 'admin_master_id', 'image_link');
        }
        if (isset($inputs['full_page'])) {
            $message .= $this->fs->uploadMultipleFiles($inputs['full_page'], $directory_images, 'AdminMasterImage', $admin_master_id, 'admin_master_id', 'image_link');
        }
        if (isset($inputs['slide_ad_area'])) {
            $message .= $this->fs->uploadMultipleFiles($inputs['slide_ad_area'], $directory_images, 'AdminMasterImage', $admin_master_id, 'admin_master_id', 'image_link');
        }

        return $message;
    }

    private function createAlterTable(int $service_id) {
        $service = $this->service->getDetailsById($service_id);
        $tableName = $this->getTableName($service->name, $service->organization->name);
        $fieldsArray = $this->getfieldArrayWithDataType($service->fields, $service->upload_data_settings->columns);
        /*
         * For update based on particular column use settings table
         * @table settings - @columns - id, table_name, column
         */
        if (\Schema::Connection(env('DB_CONNECTION'))->hasTable($tableName)) {
            return $this->createTableS->alterTable($tableName, $fieldsArray);
        }
        return $this->createTableS->createTable($tableName, $fieldsArray);
    }

    private function getTableName(string $service_name, string $org_name): string {
        $tablenameToLower = \Str::lower($org_name . ' ' . $service_name);
        return \Str::slug($tablenameToLower, '_');
    }

    private function getfieldArrayWithDataType(array $fields, array $dataTypes): array {
        $return = [];
        foreach ($fields as $k => $val) {
            $return[$k] = (isset($dataTypes[$k]) && $dataTypes[$k] !== 'unique') ? $dataTypes[$k] : 'string';
        }
        return $return;
    }

}
