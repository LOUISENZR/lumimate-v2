<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_key', 50)->unique();
            $table->enum('module', ['A', 'B', 'C']);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('category_label', 100)->nullable();
            $table->enum('input_type', ['radio', 'multi_select'])->default('radio');
            $table->string('image_path', 500)->nullable();
            $table->unsignedInteger('order_column')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('consultation_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('consultation_questions')->onDelete('cascade');
            $table->string('label', 100);
            $table->string('description', 255)->nullable();
            $table->string('value', 50);
            $table->unsignedInteger('order_column')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_options');
        Schema::dropIfExists('consultation_questions');
    }
};
