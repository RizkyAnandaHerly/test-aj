<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Seed locations tied to the first active warehouse.
     * Safe to re-run — uses firstOrCreate.
     */
    public function run(): void
    {
        // Resolve warehouse — assumes WarehouseSeeder ran first
        $wh = Warehouse::where('code', 'WH-BG-01')->first();

        if (! $wh) {
            $this->command->warn('WarehouseSeeder must run before LocationSeeder. Skipping.');
            return;
        }

        $locations = [
            // ── Zone A — rack rows (floor 1, capacity 100) ────────────────────
            ['zone' => 'A', 'rack_code' => 'A-01', 'pallet_code' => null,   'floor_level' => 1, 'capacity' => 100],
            ['zone' => 'A', 'rack_code' => 'A-02', 'pallet_code' => null,   'floor_level' => 1, 'capacity' => 100],
            ['zone' => 'A', 'rack_code' => 'A-03', 'pallet_code' => null,   'floor_level' => 1, 'capacity' => 100],
            // ── Zone A — pallet entries ────────────────────────────────────────
            ['zone' => 'A', 'rack_code' => 'A-01', 'pallet_code' => 'P-01', 'floor_level' => 1, 'capacity' => 100],
            ['zone' => 'A', 'rack_code' => 'A-01', 'pallet_code' => 'P-02', 'floor_level' => 1, 'capacity' => 100],
            // ── Zone B — rack rows (floor 1, capacity 150) ────────────────────
            ['zone' => 'B', 'rack_code' => 'B-01', 'pallet_code' => null,   'floor_level' => 1, 'capacity' => 150],
            ['zone' => 'B', 'rack_code' => 'B-02', 'pallet_code' => null,   'floor_level' => 1, 'capacity' => 150],
            ['zone' => 'B', 'rack_code' => 'B-03', 'pallet_code' => null,   'floor_level' => 1, 'capacity' => 150],
            // ── Zone C — rack rows (floor 2, capacity 200) ────────────────────
            ['zone' => 'C', 'rack_code' => 'C-01', 'pallet_code' => null,   'floor_level' => 2, 'capacity' => 200],
            ['zone' => 'C', 'rack_code' => 'C-02', 'pallet_code' => null,   'floor_level' => 2, 'capacity' => 200],
        ];

        foreach ($locations as $data) {
            Location::firstOrCreate(
                [
                    'warehouse_id' => $wh->id,
                    'zone'         => $data['zone'],
                    'rack_code'    => $data['rack_code'],
                    'pallet_code'  => $data['pallet_code'],
                ],
                [
                    'floor_level' => $data['floor_level'],
                    'capacity'    => $data['capacity'],
                    'status'      => 'available',
                ]
            );
        }

        // Back-fill warehouse_id for any existing rows that have NULL
        Location::whereNull('warehouse_id')->update(['warehouse_id' => $wh->id]);
    }
}
