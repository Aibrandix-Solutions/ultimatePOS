<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstallmentPlanLine extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'due_date' => 'date',
        'paid_on' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(\App\InstallmentPlan::class, 'installment_plan_id');
    }
}
