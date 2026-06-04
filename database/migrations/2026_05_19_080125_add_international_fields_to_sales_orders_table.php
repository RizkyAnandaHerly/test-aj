<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->text('description')->nullable()->after('status'); // Keterangan dokumen
            $table->string('origin_country')->after('description');   // Asal negara
            $table->string('destination_country')->after('origin_country'); // Tujuan negara
        });
    }

    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn(['description', 'origin_country', 'destination_country']);
        });
    }
};