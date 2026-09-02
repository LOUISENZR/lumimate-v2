<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsultationQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_key',
        'module',
        'title',
        'description',
        'category_label',
        'input_type',
        'image_path',
        'order_column',
        'is_active',
    ];

    protected $casts = [
        'order_column' => 'integer',
        'is_active' => 'boolean',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(ConsultationOption::class, 'question_id');
    }

    public function activeOptions(): HasMany
    {
        return $this->hasMany(ConsultationOption::class, 'question_id')
                    ->where('is_active', true)
                    ->orderBy('order_column');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order_column');
    }
}
