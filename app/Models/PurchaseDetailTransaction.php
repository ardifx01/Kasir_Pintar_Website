<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetailTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "transaction_id",
        "product_id",
        "quantity",
        "subtotal",
    ];

    public function transaction()
    {
        return $this->belongsTo(PurchaseTransaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
