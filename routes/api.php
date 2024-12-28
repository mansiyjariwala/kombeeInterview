<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('register',[AuthController::class,'index'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('create.user');
Route::get('login',[AuthController::class,'loginIndex'])->name('login')->middleware('redirectIfAuthenticated');
Route::post('login', [AuthController::class, 'login'])->name('login.user');
Route::get('cities/{state_id}', [AuthController::class, 'getCitiesByState'])->name('cities.state');


Route::middleware('auth.token')->group(function () {
    Route::get('dashboard',[AuthController::class,'dashboard'])->name('dashboard');
    Route::get('/logout', [DashboardController::class, 'logout'])->name('logout');
    Route::get('/users/data', [DashboardController::class, 'userData'])->name('users.data');

});
