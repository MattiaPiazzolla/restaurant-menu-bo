<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuApiController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\RestaurantStatusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/menus', [MenuApiController::class, 'index']);
Route::get('/menus/{id}', [MenuApiController::class, 'show']);
Route::get('/categories', [MenuApiController::class, 'getCategories']);
Route::get('/schedules', [ScheduleController::class, 'apiIndex']); // Moved outside auth middleware
Route::get('/restaurant-status', [RestaurantStatusController::class, 'getStatus'])->name('api.restaurant.status');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'apiUpdate']);
});