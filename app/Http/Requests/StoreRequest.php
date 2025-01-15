<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            "name" => ["required", "string", "max:255"],
            "number_phone" => ["required", "string", "max:20"],
            "postal_code" => ["required", "string", "max:10"],
            "address" => ["required", "string"],
            "url_image" => [
                "nullable",
                "image",
                "mimes:jpeg,png,jpg,gif,svg",
                "max:2048",
            ],
        ];

        if ($this->method() == "POST") {
            $rules["name"] = [
                "required",
                "string",
                "max:255",
                Rule::unique("stores")->where("owner_id", auth()->id()),
            ];
        } else {
            $rules["name"] = [
                "required",
                "string",
                "max:255",
                Rule::unique("stores")
                    ->where("owner_id", auth()->id())
                    ->ignore($this->route("store")->id),
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            "name.unique" => "Nama toko sudah digunakan.",
        ];
    }
}
