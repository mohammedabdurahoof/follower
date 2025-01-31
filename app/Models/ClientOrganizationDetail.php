<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOrganizationDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'client_id',
        'address',
        'about',
    ];
    
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
