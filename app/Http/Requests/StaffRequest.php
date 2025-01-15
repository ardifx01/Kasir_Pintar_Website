<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "role" => ["required", "string"],
            "store_id" => ["required", "exists:stores,id"],
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                Rule::unique("users"),
            ],
            "password" => ["required", "string", "min:8", "confirmed"],
            "number_phone" => ["nullable", "string", "max:20"],
        ];
    }

    public function messages()
    {
        return [
            "email.unique" => "Email sudah terdaftar.",
            "password.min" => "Password minimal 8 karakter.",
            "password.confirmed" => "Konfirmasi password tidak sesuai.",
            "role.in" => "Role tidak valid.",
            "store_id.required" => "Store ID is required.",
            "store_id.exists" => "Store ID tidak ditemukan.",
            "store_id.in" => "Store ID tidak valid.",
        ];
    }
}
