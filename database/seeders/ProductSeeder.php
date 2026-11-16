<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
                'name' => 'Macbook Pro 16',
                'description' => 'Laptop Gengsi Performa Tinggi',
                'price' => 18000000,
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1764746250417-2cc103a45a56?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
                'name' => 'iPhone 17 Pro Max 2 TB',
                'description' => 'Beli di Singapore, cash warna orange',
                'price' => 27000000,
                'stock' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
