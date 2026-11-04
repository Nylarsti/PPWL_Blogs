
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

// Halaman utama
Route::get('/', function () {
    return redirect()->route('post.index');
});

// Dashboard setelah login
Route::get('/dashboard', function () {
    return redirect()->route('post.index');
})->middleware('auth')->name('dashboard');

Route::get('/post/profile-saya', function () {
    return view('post.profile-saya');
})->middleware('auth')->name('post.profile-saya');

Route::prefix('post')->name('post.')->middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    // route resource projects, categories, technologies tetap di bawahnya
});

// Route CRUD Blog Posts (wajib login)
Route::resource('post', PostController::class)
    ->middleware('auth');

// Route Profile dari Laravel Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

Route::get('/admin-only', function () {
    return 'Selamat datang, Admin!';
})->middleware(['auth', 'admin']);

// Route Authentication
require __DIR__.'/auth.php';
