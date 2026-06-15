<?php

namespace App\Models;

use App\Enums\GameType;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        'session_id',
        'game_type',
        'date',
        'attempts',
        'won',
        'guesses',
    ];

    protected $casts = [
        'game_type' => GameType::class,
        'date' => 'date',
        'won' => 'boolean',
        'guesses' => 'array',
    ];

    
    public function scopePlayedToday($query, string $sessionId, GameType $gameType)
    {
        return $query
            ->where('session_id', $sessionId)
            ->where('game_type', $gameType->value)
            ->where('date', now()->toDateString());
    }
}