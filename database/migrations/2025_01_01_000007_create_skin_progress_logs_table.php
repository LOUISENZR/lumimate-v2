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
        Schema::create('skin_progress_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('log_date')->index();
            $table->string('photo_path', 255)->nullable();
            $table->unsignedTinyInteger('skin_condition_rating')->nullable(); // 1 to 5
            $table->text('notes')->nullable();
            $table->json('concerns_status')->nullable(); // Status of specific concerns over time
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skin_progress_logs');
    }
};
