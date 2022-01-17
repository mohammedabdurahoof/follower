<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientdataImage extends Model
{
    protected $fillable = [
        'id',
        'client_data_id',
        'image_name',
        'image_link',
    ];
    
    public function client_data(){
        return $this->belongsTo(ClientData::class);
    }
}
