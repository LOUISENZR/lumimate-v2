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
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_code', 20)->unique(); // e.g. R01, R02, F01
            $table->enum('rule_type', ['recommendation', 'frequency', 'layering', 'safety']);
            $table->json('conditions'); // IF conditions
            $table->json('actions'); // THEN actions
            $table->decimal('certainty_factor', 4, 2)->default(1.00); // CF value
            $table->text('explanation')->nullable();
            $table->string('reference_source', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
