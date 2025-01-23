<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    use HasFactory;

    protected $fillable = [
        "transaction_id",
        "customer_id",
        "amount_due",
        "payment_status",
        "due_date",
    ];

    protected $casts = [
        "due_date" => "date",
    ];

    public function transaction()
    {
        return $this->belongsTo(SellingTransaction::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentHistories()
    {
        return $this->hasMany(ReceivablePaymentHistory::class);
    }
}
