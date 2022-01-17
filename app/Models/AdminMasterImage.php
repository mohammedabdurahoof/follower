<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMasterImage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'admin_master_id',
        'image_name',
        'image_link',
    ];
    
    public function admin_master(){
        return $this->belongsTo(AdminMaster::class);
    }
}
