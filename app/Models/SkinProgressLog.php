<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkinProgressLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'log_date',
        'photo_path',
        'skin_condition_rating',
        'notes',
        'concerns_status',
    ];

    protected $casts = [
        'log_date' => 'date',
        'skin_condition_rating' => 'integer',
        'concerns_status' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
