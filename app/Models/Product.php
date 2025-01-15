<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name_product",
        "code_product",
        "selling_price",
        "purchase_price",
        "stock",
        "unit",
        "url_image",
        "store_id",
        "category_product_id",
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function categoryProduct()
    {
        return $this->belongsTo(CategoryProduct::class);
    }

    public function sellingDetailTransactions()
    {
        return $this->hasMany(SellingDetailTransaction::class);
    }

    public function purchaseDetailTransactions()
    {
        return $this->hasMany(PurchaseDetailTransaction::class);
    }
}
