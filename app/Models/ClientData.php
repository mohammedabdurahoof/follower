<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientData extends Model
{
    use HasFactory;
    
    protected $table = 'client_datas';
    
    protected $fillable = [
        'id',
        'client_id',
        'organization_id',
        'service_id',
        'file_uploaded_date',
        'file_type',
        'file_link'
    ];
    
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function client_data_images(){
        return $this->hasMany(ClientdataImage::class);
    }
    public function organization(){
        return $this->belongsTo(Organization::class);
    }
    public function service(){
        return $this->belongsTo(Service::class);
    }
    public function customer_notification(){
        return $this->hasOne(CustomerNotification::class);
    }
}
