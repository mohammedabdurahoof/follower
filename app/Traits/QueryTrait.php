<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Traits;

trait QueryTrait {
    public function scopeWithWhereHas($query, $relationship, $condition) {
        return $query->with($relationship, $condition)
                ->whereHas($relationship, $condition);
    }
    
    public function scopeOrWithWhereHas($query, $relationship, $condition) {
        return $query->with($relationship, $condition)
                ->orWhereHas($relationship, $condition);
    }
}