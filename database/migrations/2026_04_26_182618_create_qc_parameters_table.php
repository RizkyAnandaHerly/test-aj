<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qc_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qc_inspection_id')->constrained('qc_inspections')->cascadeOnDelete();
            $table->string('parameter_name');    // kondisi fisik, berat, kelembaban
            $table->string('expected_value');
            $table->string('actual_value');
            $table->enum('result', ['pass', 'fail'])->default('pass');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qc_parameters');
    }
};
