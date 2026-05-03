<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\StaffAuthController;
use App\Http\Controllers\Staff\StockEntryController;
use App\Http\Controllers\Staff\WastageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.submit');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Staff Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [StaffAuthController::class, 'showLogin'])
    ->name('staff.login');

Route::post('/login', [StaffAuthController::class, 'login'])
    ->name('staff.login.submit');

Route::post('/logout', [StaffAuthController::class, 'logout'])
    ->name('staff.logout');

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::resource('staffs', StaffController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Staff Protected Routes
|--------------------------------------------------------------------------
*/
Route::prefix('staff')->name('staff.')->middleware('staff.auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('dashboard');

    Route::get('/stock-entry', [StockEntryController::class, 'index'])
        ->name('stock-entry.index');

    Route::post('/stock-entry', [StockEntryController::class, 'store'])
        ->name('stock-entry.store');
    Route::get('/wastage', [WastageController::class, 'index'])
        ->name('wastage.index');

    Route::post('/wastage', [WastageController::class, 'store'])
        ->name('wastage.store');

    Route::delete('/wastage/{wastage}', [WastageController::class, 'destroy'])
        ->name('wastage.destroy');
});
