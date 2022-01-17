<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Admin;

use App\Models\Service;
use App\Models\UploadDataSetting;
use Illuminate\Support\Str;

/**
 * Description of FollowerserviceService
 *
 * @author Ritobroto
 */
class FollowerserviceService {

    public function getList(int $start, int $limit, $query = ""): array {
        $return['totalData'] = Service::count();
        $return['totalFiltered'] = $return['totalData'];

        $qry = Service::query();
        if ($query != "") {
            $search = Str::lower($query);

            $qry->whereRaw("LOWER(name) LIKE '%{$search}%'");

            $return['totalFiltered'] = $qry->count();
        }
        $return['data'] = $qry->with(['organization'])->offset($start)->limit($limit)->orderBy('id', 'DESC')->get();
        return $return;
    }

    public function getDetailsById(int $id): object {
        return Service::with(['organization', 'upload_data_settings'])->find($id);
    }

    public function upsertData(array $inputs): Service {
//        dd($inputs);
        if ($inputs['id'] == "") {
            $service = new Service();
        }
        if ($inputs['id'] > 0) {
            $service = Service::where('id', $inputs['id'])->firstOrfail();
        }
        foreach ($inputs as $k => $v) {
            if ($k == 'id') {
                continue;
            }
            if ($k == 'fields') {
                $v = $this->slugTheFields($v);
            }
            $service->$k = $v;
        }
//        dd($service);
        $service->save();
        return $service;
    }

    public function delete(int $id): bool {
        return Service::destroy($id);
    }

    public function saveDataTypes(array $fields, int $service_id): UploadDataSetting {
        return UploadDataSetting::updateOrCreate(
                ['service_id' => $service_id],
                [
                    'columns' => $this->dataUploadSettings($fields)
                ]
        );
    }

    private function slugTheFields(array $fields): array {
        $return = [];
        foreach ($fields as $v) {
            $v = Str::lower($v['name']);
            $return[Str::slug($v, '_')] = $v;
        }
        return $return;
    }

    private function dataUploadSettings(array $fields): array {
        $return = [];
        foreach ($fields as $v) {
            if (isset($v['data_type'])) {
                $return[Str::slug(Str::lower($v['name']), '_')] = $v['data_type'];
            }
        }
        return $return;
    }

}
