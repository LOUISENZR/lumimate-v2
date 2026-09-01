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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('skin_type', ['oily', 'dry', 'combination', 'sensitive', 'normal']);
            $table->json('skin_concerns'); // e.g. ["acne", "hyperpigmentation", "dullness"]
            $table->enum('sensitivity_level', ['resistant', 'mildly_sensitive', 'sensitive', 'very_sensitive'])->default('resistant');
            $table->enum('experience_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->enum('retinol_tolerance', ['tolerant', 'mild_sensitive', 'high_sensitive', 'unknown'])->default('unknown');
            $table->boolean('is_pregnant')->default(false);
            $table->json('special_conditions')->nullable(); // e.g. ["fragrance_allergy", "dermatologist_treatment"]
            $table->json('raw_answers')->nullable(); // raw question answers from Modul A, B, C
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
