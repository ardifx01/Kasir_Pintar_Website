<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ["gender", "age", "address", "url_image", "user_id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
