<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function consultations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function latestConsultation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Consultation::class)->latestOfMany();
    }

    public function userProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserProduct::class);
    }

    public function skincareRoutines(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SkincareRoutine::class);
    }

    public function dailyTrackers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DailyTracker::class);
    }

    public function skinProgressLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SkinProgressLog::class);
    }

    public function getStreakAttribute(): int
    {
        return DailyTracker::calculateStreak($this->id);
    }

    public function getConsistencyRateAttribute(): float
    {
        return DailyTracker::calculateConsistencyPercentage($this->id);
    }
}
