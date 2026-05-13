<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            [
                'name'           => 'PT Petani Kopi Gayo',
                'code'           => 'VND-001',
                'contact_person' => 'Bapak Hendra',
                'phone'          => '0812-1111-2222',
                'email'          => 'gayo@petanikopi.id',
                'address'        => 'Jl. Kopi No. 1, Takengon',
                'city'           => 'Banda Aceh',
                'status'         => 'active',
            ],
            [
                'name'           => 'CV Maju Bersama',
                'code'           => 'VND-002',
                'contact_person' => 'Ibu Sari',
                'phone'          => '0812-3333-4444',
                'email'          => 'sari@majubersama.co.id',
                'address'        => 'Jl. Industri No. 5, Bandung',
                'city'           => 'Bandung',
                'status'         => 'active',
            ],
            [
                'name'           => 'PT Hasil Bumi Nusantara',
                'code'           => 'VND-003',
                'contact_person' => 'Pak Joko',
                'phone'          => '021-8888-9999',
                'email'          => 'joko@hasilbumi.com',
                'address'        => 'Kawasan Industri MM2100, Bekasi',
                'city'           => 'Bekasi',
                'status'         => 'active',
            ],
        ];

        foreach ($vendors as $data) {
            Vendor::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
