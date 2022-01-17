<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAward extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'client_id',
        'name',
        'received_from',
        'achieved_date',
        'photo'
    ];
    
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
