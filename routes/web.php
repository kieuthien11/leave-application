<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\UserController;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/leave/approval', [LeaveController::class, 'approval'])->name('leave.approval')->middleware('auth');

Route::resource('leave', LeaveController::class)->middleware('auth');

Route::get('/leave/{id}', [LeaveController::class, 'show'])->name('leave.show');
Route::delete('/leave/{id}', [LeaveController::class, 'destroy'])->name('leave.destroy');
Route::put('/leave/{id}/status', [LeaveController::class, 'updateStatus']);

Route::get('/user/search', [UserController::class, 'search']);
Route::resource('user', UserController::class)->middleware(['auth']);; // Quản lý người dùng cho admin
Route::post('/user', [UserController::class, 'store']);

// Route trang index
Route::get('/', [IndexController::class, 'index'])->name('index');
