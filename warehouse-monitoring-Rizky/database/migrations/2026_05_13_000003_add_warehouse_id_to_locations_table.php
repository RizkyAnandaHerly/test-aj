<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Nullable so that existing rows (seeded before warehouses existed)
     * do not violate the constraint.  The seeder will back-fill warehouse_id
     * after running WarehouseSeeder → LocationSeeder in that order.
     */
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('warehouses')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });
    }
};
