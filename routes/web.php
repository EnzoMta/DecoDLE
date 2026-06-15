<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Game\Classic;
use App\Livewire\Game\Emoji;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::livewire('game/classic', Classic::class)->name('game.classic');
Route::livewire('game/emoji', Emoji::class)->name('game.emoji');

require __DIR__ . '/settings.php';
