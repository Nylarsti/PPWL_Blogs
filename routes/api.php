<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;

Route::middleware('throttle:portfolio-api')->group(function () {

    Route::get('/projects', [ProjectController::class, 'index']);

    Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);

});