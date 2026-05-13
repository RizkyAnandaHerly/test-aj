<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Creates three roles and one test user per role, all with password "password".
     * Run with: php artisan db:seed
     */
    public function run(): void
    {
        // ── 1. Roles ──────────────────────────────────────────────────────────
        $admin   = Role::firstOrCreate(['name' => 'admin'],   ['description' => 'Full system access']);
        $manager = Role::firstOrCreate(['name' => 'manager'], ['description' => 'Reports and approvals']);
        $staff   = Role::firstOrCreate(['name' => 'staff'],   ['description' => 'Warehouse operations']);

        // ── 2. Test users (one per role) ──────────────────────────────────────
        User::factory()->create([
            'name'     => 'Admin User',
            'email'    => 'admin@warehouse.test',
            'password' => Hash::make('password'),
            'role_id'  => $admin->id,
        ]);

        User::factory()->create([
            'name'     => 'Manager User',
            'email'    => 'manager@warehouse.test',
            'password' => Hash::make('password'),
            'role_id'  => $manager->id,
        ]);

        User::factory()->create([
            'name'     => 'Staff User',
            'email'    => 'staff@warehouse.test',
            'password' => Hash::make('password'),
            'role_id'  => $staff->id,
        ]);

        // ── 3. Roleless user (for fallback /dashboard redirect) ───────────────
        User::factory()->create([
            'name'     => 'No Role User',
            'email'    => 'norole@warehouse.test',
            'password' => Hash::make('password'),
            'role_id'  => null,
        ]);

        $this->call([
            ProductSeeder::class,
            WarehouseSeeder::class,   // must run before LocationSeeder (FK)
            VendorSeeder::class,
            LocationSeeder::class,
        ]);
    }
}
