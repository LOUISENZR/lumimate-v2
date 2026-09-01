<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('ingredient_name', 150)->index();
            $table->string('slug', 150)->unique();
            $table->enum('category', ['exfoliant', 'antioxidant', 'moisturizer', 'actives', 'soothing', 'sunscreen', 'other'])->default('actives');
            $table->text('function');
            $table->enum('usage_time', ['morning', 'night', 'both'])->default('both');
            $table->unsignedTinyInteger('max_frequency')->default(7); // Max times per week (1-7)
            $table->enum('irritation_level', ['low', 'medium', 'high'])->default('low');
            $table->boolean('safe_pregnancy')->default(true);
            $table->string('reference_source', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
