<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Traits;

/**
 *
 * @author Ritobroto
 */
trait GetTableNameTrait {
    protected function getTableName(int $serviceId, int $orgId): string {
        $org = \App\Models\Organization::findOrFail($orgId);
        $service = \App\Models\Service::findOrFail($serviceId);
        $tablenameToLower = \Str::lower($org->name . ' ' . $service->name);
        return \Str::slug($tablenameToLower, '_');
    }
}
