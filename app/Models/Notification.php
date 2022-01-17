<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    
    protected $fillable = [
        'admin_master_id',
        'notification'
    ];
    
    public function admin_master(){
        return $this->belongsTo(AdminMaster::class);
    }
}
