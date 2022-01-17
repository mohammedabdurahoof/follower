<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Traits\QueryTrait;

class Customer extends Authenticatable {

    use HasApiTokens,
        HasFactory,
        QueryTrait;

    protected $fillable = [
        'name',
        'mobile',
        'dob',
        'password',
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

    public function services() {
        return $this->belongsToMany(Service::class, 'customer_service', 'customer_id', 'service_id')
                        ->withTimestamps()->withPivot(['client_id'])
                        ->using(CustomerService::class);
    }

    public function organizations() {
        return $this->belongsToMany(Organization::class, 'customer_organization', 'customer_id', 'organization_id')
                        ->withTimestamps()->withPivot(['client_id'])
                        ->using(CustomerOrganization::class);
    }

}
