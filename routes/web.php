<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\StaffAuthController;
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
});
