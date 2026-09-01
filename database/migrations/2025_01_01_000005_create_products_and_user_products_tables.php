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
        // 1. Master Products Catalog
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 100)->index();
            $table->string('name', 200)->index();
            $table->enum('category', [
                'cleanser',
                'hydrating_toner',
                'exfoliating_toner',
                'serum',
                'spot_treatment',
                'eye_cream',
                'moisturizer',
                'face_oil',
                'sunscreen',
                'other'
            ])->default('serum');
            $table->enum('suggested_time', ['morning', 'night', 'both'])->default('both');
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });

        // 2. Product - Ingredient Pivot
        Schema::create('product_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->boolean('is_key_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'ingredient_id']);
        });

        // 3. User Products (Products in user's shelf)
        Schema::create('user_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('custom_brand', 100)->nullable();
            $table->string('custom_name', 200)->nullable();
            $table->string('custom_category', 50)->nullable();
            $table->text('custom_ingredients_raw')->nullable();
            $table->enum('usage_time', ['morning', 'night', 'both'])->default('both');
            $table->unsignedTinyInteger('frequency_per_week')->default(7);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. User Product - Ingredient Pivot (for detected actives on user's products)
        Schema::create('user_product_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_product_id')->constrained('user_products')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_product_id', 'ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_product_ingredients');
        Schema::dropIfExists('user_products');
        Schema::dropIfExists('product_ingredients');
        Schema::dropIfExists('products');
    }
};
