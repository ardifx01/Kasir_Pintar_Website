<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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

        $supplier = $this->route("supplier"); // Mendapatkan Supplier jika sedang update

        if ($supplier) {
            $rules["name"] = [
                "required",
                "string",
                "max:255",
                Rule::unique("suppliers")
                    ->ignore($supplier)
                    ->where("store_id", $this->input("store_id")),
            ];
            $rules["email"] = [
                "nullable",
                "email",
                "max:255",
                Rule::unique("suppliers")
                    ->ignore($supplier)
                    ->where("store_id", $this->input("store_id")),
            ];
            $rules["number_phone"] = [
                "nullable",
                "string",
                "max:20",
                Rule::unique("suppliers")
                    ->ignore($supplier)
                    ->where("store_id", $this->input("store_id")),
            ];
        } else {
            $rules["name"] = [
                "required",
                "string",
                "max:255",
                Rule::unique("suppliers")->where(
                    "store_id",
                    $this->input("store_id")
                ),
            ];
            $rules["email"] = [
                "nullable",
                "email",
                "max:255",
                Rule::unique("suppliers")->where(
                    "store_id",
                    $this->input("store_id")
                ),
            ];
            $rules["number_phone"] = [
                "nullable",
                "string",
                "max:20",
                Rule::unique("suppliers")->where(
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
            "name.required" => "Nama supplier harus diisi.",
            "name.unique" => "Nama supplier sudah ada di toko ini.",
            "number_phone.max" => "Nomor telepon maksimal 20 karakter.",
            "email.email" => "Format email tidak valid.",
            "email.unique" => "Email supplier sudah ada di toko ini.",
        ];
    }
}
