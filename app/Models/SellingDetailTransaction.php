<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingDetailTransaction extends Model
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
        return $this->belongsTo(SellingTransaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }
}
