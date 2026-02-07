<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantyClaim extends Model
{
    protected $guarded = ['id'];

    public const STATUS_RECEIVED_FROM_CUSTOMER = 'received_from_customer';
    public const STATUS_SENT_TO_SUPPLIER = 'sent_to_supplier';
    public const STATUS_RECEIVED_FROM_SUPPLIER = 'received_from_supplier';
    public const STATUS_RETURNED_TO_CUSTOMER = 'returned_to_customer';

    public static function statusSequence(): array
    {
        return [
            self::STATUS_RECEIVED_FROM_CUSTOMER,
            self::STATUS_SENT_TO_SUPPLIER,
            self::STATUS_RECEIVED_FROM_SUPPLIER,
            self::STATUS_RETURNED_TO_CUSTOMER,
        ];
    }

    public function transaction()
    {
        return $this->belongsTo(\App\Transaction::class, 'transaction_id');
    }

    public function sell_line()
    {
        return $this->belongsTo(\App\TransactionSellLine::class, 'sell_line_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Contact::class, 'contact_id');
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Contact::class, 'supplier_id');
    }

    public function created_by_user()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }

    public function status_logs()
    {
        return $this->hasMany(\App\WarrantyClaimStatusLog::class, 'warranty_claim_id');
    }
}
