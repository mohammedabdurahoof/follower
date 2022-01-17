<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNotification extends Model
{
    protected $fillable = [
        'client_data_id',
        'notification'
    ];
    
    public function client_data(){
        return $this->belongsTo(ClientData::class);
    }
}
