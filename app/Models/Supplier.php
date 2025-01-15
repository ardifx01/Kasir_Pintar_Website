<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        "store_id",
        "name",
        "number_phone",
        "address",
        "email",
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function payables()
    {
        return $this->hasMany(Payable::class);
    }
}
