<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users",
            ],
            "password" => ["required", "string", "min:8", "confirmed"],
            "password_confirmation" => ["required", "string"],
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "Nama wajib diisi.",
            "name.string" => "Nama harus berupa teks.",
            "name.max" => "Nama maksimal :max karakter.",
            "email.required" => "Email wajib diisi.",
            "email.string" => "Email harus berupa teks.",
            "email.email" => "Format email tidak valid.",
            "email.max" => "Email maksimal :max karakter.",
            "email.unique" => "Email sudah terdaftar.",
            "password.required" => "Password wajib diisi.",
            "password.string" => "Password harus berupa teks.",
            "password.min" => "Password minimal :min karakter.",
            "password.confirmed" => "Konfirmasi password tidak sesuai.",
            "password_confirmation.required" =>
                "Konfirmasi password wajib diisi.",
            "password_confirmation.string" =>
                "Konfirmasi password harus berupa teks.",
        ];
    }
}
