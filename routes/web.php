<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Game\Classic;
use App\Livewire\Game\Emoji;
use App\Livewire\Game\Photo;
use App\Livewire\Admin\Dashboard;


Route::view('/', 'welcome')->name('home');

Route::get('/photos/{filename}', function (string $filename) {
    $path = database_path('photos/' . basename($filename));
    abort_unless(file_exists($path), 404);
    return response()->file($path);
})->name('photos.show');

Route::livewire('game/classic', Classic::class)->name('game.classic');
Route::livewire('game/emoji', Emoji::class)->name('game.emoji');
Route::livewire('game/photo', Photo::class)->name('game.photo');

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::livewire('dashboard', Dashboard::class)->name('dashboard');
});

require __DIR__ . '/settings.php';
