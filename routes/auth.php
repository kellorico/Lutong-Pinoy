<?php


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
    // login
    Route::get('/login',[LoginController::class,'create'])->name('login');
    Route::post('/login',[LoginController::class,'store']);
    // register
    Route::get('/register',[RegisterController::class,'create'])->name('register');
    Route::post('/register',[RegisterController::class,'store']);
});

Route::middleware('auth')->group(function(){
    Route::post('/admin/logout',[LoginController::class,'destroy'])->name('admin.logout');
    Route::post('/logout',[LoginController::class,'destroy'])->name('logout');
});
