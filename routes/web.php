
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

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
