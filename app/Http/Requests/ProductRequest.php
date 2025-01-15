<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "store_id" => ["required", "exists:stores,id"],
            "category_product_id" => [
                "required",
                "exists:category_products,id",
            ],
            "name_product" => [
                "required",
                "string",
                "max:255",
                Rule::unique("products")
                    ->where("store_id", $this->input("store_id"))
                    ->ignore($this->route("product")),
            ],
            "code_product" => [
                "required",
                "string",
                "max:255",
                Rule::unique("products")
                    ->where("store_id", $this->input("store_id"))
                    ->ignore($this->route("product")),
            ],
            "selling_price" => ["required", "numeric", "min:0"],
            "purchase_price" => ["required", "numeric", "min:0"],
            "stock" => ["required", "integer", "min:0"],
            "unit" => ["required", "string", "max:255"],
            "url_image" => [
                "nullable",
                "image",
                "mimes:jpeg,png,jpg,gif,svg",
                "max:2048",
            ],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            "store_id.required" => "Toko harus dipilih.",
            "store_id.exists" => "Toko tidak ditemukan.",
            "category_product_id.required" => "Kategori produk harus dipilih.",
            "category_product_id.exists" => "Kategori produk tidak ditemukan.",
            "name_product.required" => "Nama produk harus diisi.",
            "name_product.unique" => "Nama produk sudah ada di toko ini.",
            "code_product.required" => "Kode produk harus diisi.",
            "code_product.unique" => "Kode produk sudah ada di toko ini.",
            "selling_price.required" => "Harga jual harus diisi.",
            "selling_price.numeric" => "Harga jual harus berupa angka.",
            "selling_price.min" => "Harga jual minimal 0.",
            "purchase_price.required" => "Harga beli harus diisi.",
            "purchase_price.numeric" => "Harga beli harus berupa angka.",
            "purchase_price.min" => "Harga beli minimal 0.",
            "stock.required" => "Stok harus diisi.",
            "stock.integer" => "Stok harus berupa angka bulat.",
            "stock.min" => "Stok minimal 0.",
            "unit.required" => "Satuan harus diisi.",
            "url_image.image" => "File harus image",
        ];
    }
}
