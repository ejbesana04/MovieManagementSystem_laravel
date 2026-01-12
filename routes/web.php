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
    $query = App\Models\Movie::with('genre');

    if (request()->filled('search')) {
        $searchTerm = request('search');
        $query->where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('director', 'like', "%{$searchTerm}%");
        });
    }

    if (request()->filled('genre_filter') && request('genre_filter') != '') {
        $query->where('genre_id', request('genre_filter'));
    }

    $movies = $query->latest()->get();
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

// ✅ Add your Movie System routes BELOW

Route::middleware(['auth'])->group(function () {

    //Movie Soft Delete / Trash Management
    Route::get('/movies/trash', [MovieController::class, 'trash'])->name('movies.trash');
    Route::post('/movies/{id}/restore', [MovieController::class, 'restore'])->name('movies.restore');
    Route::delete('/movies/{id}/force-delete', [MovieController::class, 'forceDelete'])->name('movies.force-delete');

    Route::get('/movies/export', [MovieController::class, 'export'])->name('movies.export');

    // Movies
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::post('/movies', [MovieController::class, 'store'])->name('movies.store');
    Route::put('/movies/{movie}', [MovieController::class, 'update'])->name('movies.update');
    Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');

    // Genres
    Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
    Route::post('/genres', [GenreController::class, 'store'])->name('genres.store');
    Route::put('/genres/{genre}', [GenreController::class, 'update'])->name('genres.update');
    Route::delete('/genres/{genre}', [GenreController::class, 'destroy'])->name('genres.destroy');
});
