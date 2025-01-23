<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayablePaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        "payable_id",
        "payment_date",
        "amount_paid",
        "payment_method",
        "description",
    ];

    protected $casts = [
        "payment_date" => "date",
    ];

    public function payable()
    {
        return $this->belongsTo(Payable::class);
    }
}
