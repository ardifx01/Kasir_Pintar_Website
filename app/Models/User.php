<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ["name", "email", "password", "number_phone", "role"];

    protected $hidden = ["password", "remember_token"];

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class, "owner_id");
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function isAdmin()
    {
        return $this->role == "admin";
    }

    public function isOwner()
    {
        return $this->role == "owner";
    }

    public function isStaff()
    {
        return $this->role == "staff";
    }

    public function ownsStore($storeId)
    {
        return $this->stores()->where("id", $storeId)->exists();
    }
}
