<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $guard = 'client';
    
    protected $fillable = [
        'id',
        'organization_id',
        'name',
        'mobile',
        'dob',
        'password',
        'dom',
        'email',
        'place',
        'cadre',
        'product',
        'p_date',
        'r_date',
        'status',
        'logo'
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function organization(){
        return $this->belongsTo(Organization::class);
    }
    public function services(){
        return $this->belongsToMany(Service::class)->withTimestamps();
    }
    public function policies(){
        return $this->hasMany(Policy::class);
    }
    
    public function client_organization_detail(){
        return $this->hasOne(ClientOrganizationDetail::class);
    }
    public function client_award(){
        return $this->hasMany(ClientAward::class);
    }
    public function client_data(){
        return $this->hasMany(ClientData::class);
    }
}
