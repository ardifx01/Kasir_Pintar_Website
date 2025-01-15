<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryProduct;
use Illuminate\Support\Facades\Storage;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                "name" => "Makanan & Minuman",
                "name_img" => "makanan_minuman.png",
            ],
            [
                "name" => "Perlengkapan Rumah Tangga",
                "name_img" => "perlengkapan_rumah_tangga.png",
            ],
            [
                "name" => "Elektronik & Teknologi",
                "name_img" => "elektronik_teknologi.png",
            ],
            [
                "name" => "Pakaian & Aksesoris",
                "name_img" => "pakaian_aksesoris.png",
            ],
            [
                "name" => "Kesehatan & Kecantikan",
                "name_img" => "kesehatan_kecantikan.png",
            ],
            [
                "name" => "Hobi & Kebutuhan Khusus",
                "name_img" => "hobi_kebutuhan_khusus.png",
            ],
            [
                "name" => "Alat Tulis & Kantor (ATK)",
                "name_img" => "alat_tulis_kantor.png",
            ],
            ["name" => "Bahan Bangunan", "name_img" => "bahan_bangunan.png"],
        ];

        foreach ($categories as $category) {
            CategoryProduct::create([
                "name" => $category["name"],
                "name_img" => $category["name_img"],
            ]);
        }
    }
}
