<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    protected $table = 'people';

    protected $fillable = [
        'first_name',
        'last_name',
        'photo_path',
        'description',
        'class',
        'gender',
        'age',
        'height',
        'hair_color',
        'city',
        'origin',
        'hobby',
        'specialization',
        'emoji_1',
        'emoji_2',
        'emoji_3',
        'emoji_4',
    ];

    protected $casts = [
        'age' => 'integer',
        'height' => 'integer',
    ];


    public function dailyPeople(): HasMany
    {
        return $this->hasMany(DailyPerson::class);
    }


    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFormattedHeightAttribute(): string
    {
        $meters = floor($this->height / 100);
        $cm = $this->height % 100;
        return "{$meters}m" . str_pad($cm, 2, '0', STR_PAD_LEFT);
    }

    public function getComparableAttributes(): array
    {
        return [
            'class'          => $this->class,
            'gender'         => $this->gender,
            'age'            => $this->age,
            'height'         => $this->height,
            'hair_color'     => $this->hair_color,
            'city'           => $this->city,
            'hobby'          => $this->hobby,
            'specialization' => $this->specialization,
        ];
    }
}