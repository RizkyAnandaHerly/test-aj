<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'sku' => 'RAW-GB-GAYO-G1',
                'name' => 'Green Beans Arabica Gayo Grade 1',
                'category' => 'Raw Material',
                'unit' => 'Kg',
                'description' => 'Biji kopi mentah Gayo kualitas ekspor, origin Aceh Tengah.',
                'stock_qty' => 0,
                'min_stock' => 1000, // Minimal 1 Ton
                'status' => 'active',
            ],
            [
                'sku' => 'RAW-GB-LMP-R1',
                'name' => 'Green Beans Robusta Lampung Grade 1',
                'category' => 'Raw Material',
                'unit' => 'Kg',
                'description' => 'Biji kopi mentah Robusta kualitas ekspor, origin Lampung.',
                'stock_qty' => 0,
                'min_stock' => 2000,
                'status' => 'active',
            ],
            [
                'sku' => 'PKG-JUTE-60KG',
                'name' => 'Jute Bag 60kg Export Standard',
                'category' => 'Packaging',
                'unit' => 'Pcs',
                'description' => 'Karung goni standar ekspor kapasitas 60kg dengan liner.',
                'stock_qty' => 0,
                'min_stock' => 500,
                'status' => 'active',
            ],
            [
                'sku' => 'FG-ROAST-GAYO-MED',
                'name' => 'Roasted Arabica Gayo - Medium Roast',
                'category' => 'Finished Goods',
                'unit' => 'Kg',
                'description' => 'Kopi sangrai Medium Roast Gayo untuk pasar Eropa.',
                'stock_qty' => 0,
                'min_stock' => 100,
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}