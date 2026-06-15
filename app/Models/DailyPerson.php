<?php

namespace App\Models;

use App\Enums\GameType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyPerson extends Model
{
    protected $fillable = [
        'person_id',
        'game_type',
        'date',
    ];

    protected $casts = [
        'game_type' => GameType::class,
        'date' => 'date',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    
    public function scopeForToday($query, GameType $gameType)
    {
        return $query
            ->where('game_type', $gameType->value)
            ->where('date', now()->toDateString());
    }
}