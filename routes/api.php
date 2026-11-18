
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\CategoryController;

Route::middleware('throttle:portfolio-api')->group(function () {

    Route::get('/projects', [ProjectController::class, 'index']);

    Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);

    Route::get('/categories', [CategoryController::class, 'index']);

});
