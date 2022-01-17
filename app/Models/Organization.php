<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Traits\QueryTrait;

class Organization extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'name',
        'logo',
        'status',
    ];
    
    public function service(){
        return $this->hasMany(Service::class);
    }
    public function admin_master(){
        return $this->hasMany(AdminMaster::class);
    }
    public function client(){
        return $this->hasMany(Client::class);
    }
    public function client_data(){
        return $this->hasMany(ClientData::class);
    }
    public function customers(){
        return $this->belongsToMany(Customer::class)->withTimestamps();
    }
    public function policies(){
        return $this->hasMany(Policy::class);
    }
}
