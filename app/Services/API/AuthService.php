<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\API;

use App\Models\Customer;

/**
 * Description of AuthService
 *
 * @author Ritobroto
 */
class AuthService {
    public function customerExist(string $phone) {
        return Customer::where('mobile', $phone)->first();
    }
}
