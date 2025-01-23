<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceivablePaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        "receivable_id",
        "payment_date",
        "amount_paid",
        "payment_method",
        "description",
    ];

    protected $casts = [
        "payment_date" => "date",
    ];

    public function receivable()
    {
        return $this->belongsTo(Receivable::class);
    }
}
