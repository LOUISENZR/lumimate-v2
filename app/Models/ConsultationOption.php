<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'label',
        'description',
        'value',
        'order_column',
        'is_active',
    ];

    protected $casts = [
        'order_column' => 'integer',
        'is_active' => 'boolean',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(ConsultationQuestion::class, 'question_id');
    }
}
