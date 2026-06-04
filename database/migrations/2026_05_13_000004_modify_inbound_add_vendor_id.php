<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Replace free-text `supplier` column with a proper FK to vendors.
     * vendor_id is nullable so existing inbound records aren't broken.
     */
    public function up(): void
    {
        Schema::table('inbound', function (Blueprint $table) {
            // Add the new FK column first
            $table->foreignId('vendor_id')
                  ->nullable()
                  ->after('qty')
                  ->constrained('vendors')
                  ->nullOnDelete();
        });

        Schema::table('inbound', function (Blueprint $table) {
            // Drop the old free-text column
            $table->dropColumn('supplier');
        });
    }

    public function down(): void
    {
        Schema::table('inbound', function (Blueprint $table) {
            $table->string('supplier')->nullable()->after('qty');
        });

        Schema::table('inbound', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn('vendor_id');
        });
    }
};
