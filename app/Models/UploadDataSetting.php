<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadDataSetting extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'columns' => 'array',
    ];

    protected $fillable = [
        'id',
        'service_id',
        'columns',
    ];
    
    public function service() {
        return $this->belongsTo(Service::class);
    }
}
