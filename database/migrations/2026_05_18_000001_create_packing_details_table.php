<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inbound_id')->constrained('inbound')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('packer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('packaging_type', 100);
            $table->string('package_weight', 50)->nullable();
            $table->string('package_dimensions', 100)->nullable();
            $table->string('label_code')->unique();
            $table->text('notes')->nullable();
            $table->timestamp('label_printed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packing_details');
    }
};
