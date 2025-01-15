<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "store_id",
        "total_discount",
        "total_tax",
        "is_debt",
        "description",
        "payment_method",
        "total_amount",
        "amount_paid",
        "change_amount",
        "transaction_status",
    ];

    protected $casts = [
        "is_debt" => "boolean",
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function purchaseDetailTransactions()
    {
        return $this->hasMany(PurchaseDetailTransaction::class);
    }

    public function payable()
    {
        return $this->hasOne(Payable::class);
    }
}
