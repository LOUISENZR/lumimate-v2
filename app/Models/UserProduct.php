<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'custom_brand',
        'custom_name',
        'custom_category',
        'custom_ingredients_raw',
        'usage_time',
        'frequency_per_week',
        'is_active',
    ];

    protected $casts = [
        'frequency_per_week' => 'integer',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'user_product_ingredients')
                    ->withTimestamps();
    }

    public function routineItems(): HasMany
    {
        return $this->hasMany(RoutineItem::class);
    }

    /**
     * Get display brand name (custom or master)
     */
    public function getDisplayBrandAttribute(): string
    {
        return $this->product ? $this->product->brand : ($this->custom_brand ?? 'Custom Brand');
    }

    /**
     * Get display product name (custom or master)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->product ? $this->product->name : ($this->custom_name ?? 'Custom Product');
    }

    /**
     * Get display category
     */
    public function getDisplayCategoryAttribute(): string
    {
        return $this->product ? $this->product->category : ($this->custom_category ?? 'serum');
    }
}
