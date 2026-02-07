<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantyClaimStatusLog extends Model
{
    protected $guarded = ['id'];

    public function warranty_claim()
    {
        return $this->belongsTo(\App\WarrantyClaim::class, 'warranty_claim_id');
    }

    public function created_by_user()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }
}
