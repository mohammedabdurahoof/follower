<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CustomerOrganization extends Pivot
{
    public function client() {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
