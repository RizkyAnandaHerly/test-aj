<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            [
                'name'     => 'Gudang Utama Bogor',
                'code'     => 'WH-BG-01',
                'address'  => 'Jl. Raya Bogor No. 10, Bogor Selatan',
                'pic_name' => 'Rizky Ananda',
                'phone'    => '0251-123456',
                'status'   => 'active',
            ],
            [
                'name'     => 'Gudang Cabang Jakarta',
                'code'     => 'WH-JKT-01',
                'address'  => 'Kawasan Industri Pulo Gadung, Jakarta Timur',
                'pic_name' => 'Daffa Izzati',
                'phone'    => '021-9876543',
                'status'   => 'active',
            ],
        ];

        foreach ($warehouses as $data) {
            Warehouse::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
