<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingTransaction extends Model
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

    public function sellingDetailTransactions()
    {
        return $this->hasMany(SellingDetailTransaction::class);
    }

    public function receivable()
    {
        return $this->hasOne(Receivable::class);
    }
}
