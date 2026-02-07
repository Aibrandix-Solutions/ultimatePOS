<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'first_due_date' => 'date',
        'closed_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(\App\Transaction::class, 'transaction_id');
    }

    public function contact()
    {
        return $this->belongsTo(\App\Contact::class, 'contact_id');
    }

    public function lines()
    {
        return $this->hasMany(\App\InstallmentPlanLine::class, 'installment_plan_id');
    }
}
