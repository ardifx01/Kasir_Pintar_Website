<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    use HasFactory;

    protected $fillable = [
        "transaction_id",
        "supplier_id",
        "amount_due",
        "payment_status",
        "due_date",
    ];

    protected $casts = [
        "due_date" => "date",
    ];

    public function transaction()
    {
        return $this->belongsTo(PurchaseTransaction::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
