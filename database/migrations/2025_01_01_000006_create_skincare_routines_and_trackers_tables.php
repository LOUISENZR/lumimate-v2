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
        // 1. Skincare Routines (Generated Plan)
        Schema::create('skincare_routines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('consultation_id')->nullable()->constrained('consultations')->onDelete('set null');
            $table->enum('routine_type', ['morning', 'night', 'skin_cycling'])->default('morning');
            $table->enum('day_of_week', ['all', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])->default('all');
            $table->enum('cycling_phase', ['exfoliation', 'retinoid', 'recovery', 'daily'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Routine Items (Layering Steps in each routine)
        Schema::create('routine_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routine_id')->constrained('skincare_routines')->onDelete('cascade');
            $table->foreignId('user_product_id')->constrained('user_products')->onDelete('cascade');
            $table->unsignedTinyInteger('step_order'); // 1-9 Layering order
            $table->string('category', 50);
            $table->text('usage_instructions')->nullable();
            $table->timestamps();
        });

        // 3. Daily Trackers (Daily checklist log)
        Schema::create('daily_trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('routine_item_id')->constrained('routine_items')->onDelete('cascade');
            $table->date('tracked_date')->index();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->string('notes', 255)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'routine_item_id', 'tracked_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_trackers');
        Schema::dropIfExists('routine_items');
        Schema::dropIfExists('skincare_routines');
    }
};
