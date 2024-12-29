<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\AuthenticateToken;
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
        // Route::middleware(['auth.token:3,4'])->group(function () {
            Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
            Route::get('/users/data', [DashboardController::class, 'userData'])->name('users.data');
        // });

            Route::prefix('admin')->group(function () {
                Route::middleware('auth.token:1')->group(function () {
                Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

                //role
                Route::prefix('role')->group(function () {
                    Route::get('/', [RoleController::class, 'index'])->name('admin.role');
                    Route::get('/data', [RoleController::class, 'roleData'])->name('role.data');
                    Route::get('/destroy/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
                    Route::get('/create', [RoleController::class, 'create'])->name('role.create');
                    Route::post('/store', [RoleController::class,'store'])->name('role.store');
                    Route::get('/edit/{id}', [RoleController::class,'edit'])->name('role.edit');
                    Route::put('/{id}', [RoleController::class,'update'])->name('role.update');
                });

                //permission
                Route::prefix('permission')->group(function () {
                    Route::get('/', [PermissionController::class, 'index'])->name('admin.permission');
                    Route::get('/data', [PermissionController::class, 'permissionData'])->name('permission.data');
                    Route::get('/destroy/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');
                    Route::get('/create', [PermissionController::class, 'create'])->name('permission.create');
                    Route::post('/store', [PermissionController::class,'store'])->name('permission.store');
                    Route::get('/edit/{id}', [PermissionController::class,'edit'])->name('permission.edit');
                    Route::put('/{id}', [PermissionController::class,'update'])->name('permission.update');
                });

                //supplier
                Route::prefix('supplier')->group(function () {
                    Route::get('/', [SupplierController::class, 'index'])->name('admin.supplier');
                    Route::get('/data', [SupplierController::class, 'supplierData'])->name('supplier.data');
                    Route::get('/destroy/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
                    Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create');
                    Route::post('/store', [SupplierController::class,'store'])->name('supplier.store');
                    Route::get('/edit/{id}', [SupplierController::class,'edit'])->name('supplier.edit');
                    Route::put('/{id}', [SupplierController::class,'update'])->name('supplier.update');
                });

                //customer
                Route::prefix('customer')->group(function () {
                    Route::get('/', [CustomerController::class, 'index'])->name('admin.customer');
                    Route::get('/data', [CustomerController::class, 'customerData'])->name('customer.data');
                    Route::get('/destroy/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
                    Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
                    Route::post('/store', [CustomerController::class,'store'])->name('customer.store');
                    Route::get('/edit/{id}', [CustomerController::class,'edit'])->name('customer.edit');
                    Route::put('/{id}', [CustomerController::class,'update'])->name('customer.update');
                });

                Route::get('cities/{state_id}', [AuthController::class, 'getCitiesByState'])->name('admin.cities.state');


            });
        });

    Route::get('/logout', [DashboardController::class, 'logout'])->name('logout');
});
