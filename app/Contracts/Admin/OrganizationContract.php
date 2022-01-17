<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Contracts\Admin;

use App\Models\Organization;

/**
 *
 * @author Ritobroto
 */
interface OrganizationContract {
    public function getList(int $start, int $limit, $search_value=""): array;
    public function orgDetailsById(int $id): object;
    public function upsertData(array $inputs): Organization;
}
