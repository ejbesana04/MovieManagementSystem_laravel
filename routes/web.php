<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\GenreController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', function () {
    $movies = App\Models\Movie::with('genre')->get();
    $genres = App\Models\Genre::all();

    return view('dashboard', compact('movies', 'genres'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::post('/movies', [MovieController::class, 'store'])->name('movies.store');
Route::put('/movies/{movie}', [MovieController::class, 'update'])->name('movies.update');
Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');

Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
Route::post('/genres', [GenreController::class, 'store'])->name('genres.store');
Route::put('/genres/{genre}', [GenreController::class, 'update'])->name('genres.update');
Route::delete('/genres/{genre}', [GenreController::class, 'destroy'])->name('genres.destroy');