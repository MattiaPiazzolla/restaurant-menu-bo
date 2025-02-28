<?php
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RestaurantStatusController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('menus', MenuController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/reservations/past', [ReservationController::class, 'destroyPast'])->name('reservations.destroyPast');
    Route::resource('reservations', ReservationController::class);
    Route::patch('/reservations/{reservation}/toggle-arrived', [ReservationController::class, 'toggleArrived'])->name('reservations.toggleArrived');
    Route::get('/restaurant-status', [RestaurantStatusController::class, 'index'])->name('restaurant-status.index');
    Route::patch('/restaurant-status/toggle', [RestaurantStatusController::class, 'toggle'])->name('restaurant-status.toggle');
});

// API Route (public)
Route::get('/api/restaurant-status', [RestaurantStatusController::class, 'getStatus']);

require __DIR__.'/auth.php';