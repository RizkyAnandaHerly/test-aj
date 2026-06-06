<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reject_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inbound_id')->constrained('inbound')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('inspector_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('qty_rejected');
            $table->enum('category', ['reject', 'quarantine'])->default('reject');
            $table->string('quarantine_location')->nullable();
            $table->text('reason');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reject_items');
    }
};
