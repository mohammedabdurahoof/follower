<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryTrait;

class Service extends Model {

    use HasFactory,
        QueryTrait;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fields' => 'array',
    ];

    protected $fillable = [
        'id',
        'organization_id',
        'name',
        'status',
        'fields',
    ];

    public function organization() {
        return $this->belongsTo(Organization::class);
    }
    
    public function upload_data_settings() {
        return $this->hasOne(UploadDataSetting::class);
    }
    
    public function admin_master() {
        return $this->hasMany(AdminMaster::class);
    }

    public function clients() {
        return $this->belongsToMany(Client::class)->withTimestamps();
    }

    public function client_data() {
        return $this->hasMany(ClientData::class);
    }

    public function customers() {
        return $this->belongsToMany(Customer::class)->withTimestamps();
    }

}
