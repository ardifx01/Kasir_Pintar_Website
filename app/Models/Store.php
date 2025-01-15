<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        "owner_id",
        "name",
        "number_phone",
        "postal_code",
        "address",
        "url_image",
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function sellingTransactions()
    {
        return $this->hasMany(SellingTransaction::class);
    }

    public function purchaseTransactions()
    {
        return $this->hasMany(PurchaseTransaction::class);
    }
}
