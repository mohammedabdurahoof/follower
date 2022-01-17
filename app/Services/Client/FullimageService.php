<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Client;

use App\Models\AdminMaster;
use App\Models\AdminMasterImage;
use App\Models\Client;

/**
 * Description of FullimageService
 *
 * @author Ritobroto
 */
class FullimageService {

    public function getList(int $start, int $limit, $query = ""): array {
        $qury = AdminMaster::withWhereHas('organization', function ($query) {
                    $query->where("id", auth()->user()->organization_id);
                })->with('service');
        $qry = $qury;
        $return['totalData'] = $qury->count();
        $return['totalFiltered'] = $return['totalData'];
        if ($query != "") {
            $search = strtolower($query);

            $qry->orWhereHas('service', function ($query) use ($search) {
                $query->whereRaw("LOWER(name) LIKE '%{$search}%'");
            });

            $return['totalFiltered'] = $qry->count();
        }

        $return['data'] = $qry->where('organization_id', auth()->user()->organization_id)->where("file_type", "images")->where("user_display_type", 2)
                        ->offset($start)->limit($limit)->orderBy('id', 'DESC')->get();
        return $return;
    }

    public function getDetailsById(int $id): AdminMaster {
        return AdminMaster::with(['service', 'organization', 'admin_master_image'])
                        ->find($id);
    }
    
    public function getTopImageById(int $id) {
        $image = AdminMasterImage::findOrFail($id);
        if(isset($image->image_link) && \Storage::exists($image->image_link)){
            return $image->image_link;
        }
        return NULL;
    }
    
    public function getBottomImageById(int $id) {
        $image = Client::findOrFail($id);
        if(isset($image->bottom_image) && \Storage::exists($image->bottom_image)){
            return $image->bottom_image;
        }
        return NULL;
    }

}
