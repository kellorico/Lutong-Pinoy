<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DessertsController;
use App\Http\Controllers\DrinksController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SnacksController;
use App\Http\Middleware\Admin;
use App\Models\Order;
use Illuminate\Support\Facades\Route;


//Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class,'index'])->name('admin.dashboard');
    Route::get('/admin/categories', [CategoryController::class, 'create'])
        ->name('admin.categories');

    Route::post('/admin/categories/store', [CategoryController::class, 'store'])
        ->name('admin.categories.store');

    Route::put('/admin/categories/update/{id}', [CategoryController::class, 'update'])
        ->name('admin.categories.update');

    Route::delete('/admin/categories/delete/{id}', [CategoryController::class, 'destroy'])
        ->name('admin.categories.delete');

    //admin menu
    Route::get('/admin/menus', [MenuController::class, 'create'])
        ->name('admin.menus');

    Route::post('/admin/menus/store', [MenuController::class, 'store'])
        ->name('admin.menus.store');

    Route::put('/admin/menus/update/{id}', [MenuController::class, 'update'])
        ->name('admin.menus.update');

    Route::delete('/admin/menus/delete/{id}', [MenuController::class, 'destroy'])
        ->name('admin.menus.delete');

    //Orders
    Route::get('/admin/orders',[OrderController::class,'index'])->name('admin.orders');
    Route::post('/admin/orders',[OrderController::class,'store']);
    Route::put('/admin/orders/{id}',[OrderController::class,'update']);
    Route::delete('/admin/orders/{id}',[OrderController::class,'destroy']);

    //admin users
    Route::get('/admin/users', [AdminUserController::class, 'index'])
        ->name('admin.users');

    Route::post('/admin/users/store', [AdminUserController::class, 'store'])
        ->name('admin.users.store');

    Route::put('/admin/users/update/{id}', [AdminUserController::class, 'update'])
        ->name('admin.users.update');

    Route::delete('/admin/users/delete/{id}', [AdminUserController::class, 'destroy'])
        ->name('admin.users.delete');

    //Profile
    Route::get('/admin/profile', [ProfileController::class, 'index'])->name('admin.profile');
});


//user
Route::get('/', function () {
    return inertia('Welcome');
})->name('welcome')->middleware('guest');

Route::middleware(['auth', 'role:customer'])->group(function(){
    Route::get('/menus', [MenuController::class, 'index'])->name('dashboard');
    //add to cart
    Route::post('/cart/add/{id}',[OrderController::class,'add']);
    Route::post('/cart/decrease/{id}',[OrderController::class,'decrease']);
    Route::post('/cart/remove/{id}',[OrderController::class,'remove']);

    //profile
    Route::get('/profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class,'updateInfo'])->name('profile.info');
    Route::put('/profile',[ProfileController::class,'updatePassword'])->name('profile.password');
    Route::delete('/profile',[ProfileController::class,'destroy'])->name('profile.destroy');

    //order details
    Route::get('/orderdetails',[OrderDetailsController::class,'index'])->name('order.details');
});

Route::post('/orders', [OrderController::class, 'store'])->middleware(['auth']);

require __DIR__ . '/auth.php';
