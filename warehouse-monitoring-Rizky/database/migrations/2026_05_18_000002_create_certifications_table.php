<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inbound_id')->constrained('inbound')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('certifier_id')->constrained('users')->cascadeOnDelete();
            $table->date('certification_date');
            $table->string('certification_type', 100);
            $table->string('lot_number', 100);
            $table->string('standard_region', 50)->default('Eropa');
            $table->string('document_path');
            $table->string('document_name');
            $table->string('status', 50)->default('valid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
