<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Game\Classic;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::livewire('game/classic', Classic::class)->name('game.classic');

require __DIR__ . '/settings.php';
