<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Utils;

/**
 * Description of ApplicationUtils
 *
 * @author Ritobroto
 */
class ApplicationUtils {
    public static function getStatus(): array{
        return [
            1 => 'Active',
            2 => 'In-Active'
        ];
    }
    
    public static function getPaginationReturnData(object $request, int $totalData, int $totalFiltered, array $result): array{
        return [
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $result   
            ];
    }
    
    public static function adminUserDetails(): array {
        return [
            1 => "Page Top",
            2 => "Full Page",
            3 => "Slide Ad Area",
        ];
    }
    
    public static function uploadAbleFileType(string $user): array {
        $admin = [
            'pdf' =>  '<i class="far fa-file-pdf" aria-hidden="true"></i>',
            'images' => '<i class="far fa-file-image" aria-hidden="true"></i>',
            'txt' =>  '<i class="far fa-file-alt" aria-hidden="true"></i>',
        ];
        $client = [
            'xls' => '<i class="far fa-file-excel" aria-hidden="true"></i>',
            'txt' =>  '<i class="far fa-file-alt" aria-hidden="true"></i>',
        ];
        if($user == 'admin') {
            return $admin;
        }
        return $client;
    }
    
    public static function getExceptColumns(): array{
        return ['name', 'mobile', 'dob', 'id', 'client_id', 'customer_id', 'created_at', 'updated_at'];
    }
    
    
}
