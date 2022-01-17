<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryTrait;

class AdminMaster extends Model {

    use HasFactory,
        QueryTrait;

    protected $fillable = [
        'id',
        'organization_id',
        'service_id',
        'user_display_type',
        'file_type',
        'file_name',
        'file_link'
    ];

    public function organization() {
        return $this->belongsTo(Organization::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function admin_master_image() {
        return $this->hasMany(AdminMasterImage::class);
    }

    public function notification() {
        return $this->hasOne(Notification::class);
    }

}
