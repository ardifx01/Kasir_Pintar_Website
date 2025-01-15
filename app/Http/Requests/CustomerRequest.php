<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ganti dengan logika otorisasi jika diperlukan
    }

    public function rules(): array
    {
        $rules = [
            "store_id" => ["required", "exists:stores,id"],
            "name" => ["required", "string", "max:255"],
            "number_phone" => ["nullable", "string", "max:20"],
            "address" => ["nullable", "string"],
            "email" => ["nullable", "email", "max:255"],
        ];

        // Gunakan ignore() hanya jika sedang mengupdate
        $customer = $this->route("customer");
        if ($customer) {
            $rules["name"] = [
                "required",
                "string",
                "max:255",
                Rule::unique("customers")
                    ->ignore($customer)
                    ->where("store_id", $this->input("store_id")),
            ];
            $rules["email"] = [
                "nullable",
                "email",
                "max:255",
                Rule::unique("customers")
                    ->ignore($customer)
                    ->where("store_id", $this->input("store_id")),
            ];
            $rules["number_phone"] = [
                "nullable",
                "string",
                "max:20",
                Rule::unique("customers")
                    ->ignore($customer)
                    ->where("store_id", $this->input("store_id")),
            ];
        } else {
            $rules["name"] = [
                "required",
                "string",
                "max:255",
                Rule::unique("customers")->where(
                    "store_id",
                    $this->input("store_id")
                ),
            ];
            $rules["email"] = [
                "nullable",
                "email",
                "max:255",
                Rule::unique("customers")->where(
                    "store_id",
                    $this->input("store_id")
                ),
            ];
            $rules["number_phone"] = [
                "nullable",
                "string",
                "max:20",
                Rule::unique("customers")->where(
                    "store_id",
                    $this->input("store_id")
                ),
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            "store_id.required" => "Toko harus dipilih.",
            "store_id.exists" => "Toko tidak ditemukan.",
            "name.required" => "Nama customer harus diisi.",
            "name.unique" => "Nama customer sudah ada di toko ini.",
            "number_phone.max" => "Nomor telepon maksimal 20 karakter.",
            "email.email" => "Format email tidak valid.",
            "email.unique" => "Email customer sudah ada di toko ini.",
        ];
    }
}
