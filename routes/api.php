<?php
use App\Http\Controllers\Api\FoodController as ApiFoodController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    Route::get('/foods', [ApiFoodController::class, 'index'])->name('api.foods.index');
    Route::get('/foods/{food:slug}', [ApiFoodController::class, 'show'])->name('api.foods.show');
});
