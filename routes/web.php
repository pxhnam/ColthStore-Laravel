<?php

use App\Enums\UserRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

Route::get('welcome', function () {
    return 'Hi!';
});


Route::middleware('guest')
    ->group(function () {
        Route::get('login', [AuthController::class, 'login'])->name('login');
        Route::post('login', [AuthController::class, 'handleLogin'])->name('login');

        Route::get('register', [AuthController::class, 'register'])->name('register');
        Route::post('register', [AuthController::class, 'handleRegister'])->name('register');

        Route::get('account-verification', [AuthController::class, 'verification'])->name('verification');
        Route::post('account-verification', [AuthController::class, 'verify'])->name('verification');

        Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('refresh-token');
    });


Route::get('', [HomeController::class, 'index'])->name('home');
Route::post('add-to-cart', [CartController::class, 'add'])->name('add-to-cart');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::prefix('products')
    ->name('products.')
    ->group(function () {
        Route::get('', [ProductController::class, 'index'])->name('index');
        Route::get('get/{id}', [ProductController::class, 'get'])->name('get');
        Route::get('{slug}', [ProductController::class, 'show'])->name('show');
    });

Route::middleware('auth')
    ->group(function () {
        Route::get('carts', [CartController::class, 'index'])->name('carts');
        Route::get('my-orders', [OrderController::class, 'index'])->name('orders');
    });




Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('', [AdminAuthController::class, 'login'])
            ->name('login');
        Route::post('', [AdminAuthController::class, 'handleLogin'])
            ->middleware('guest')
            ->name('login');

        Route::middleware('authorize:admin')
            ->group(function () {
                Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

                Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
                // Route::resource('users', UserController::class);

                Route::prefix('users')
                    ->name('users.')
                    ->group(function () {
                        Route::get('', [UserController::class, 'index'])->name('index');
                        Route::get('create', [UserController::class, 'create'])->name('create');
                        Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
                    });

                Route::get('colors', [ColorController::class, 'index'])->name('colors');
                Route::get('sizes', [SizeController::class, 'index'])->name('sizes');
                Route::get('categories', [CategoryController::class, 'index'])->name('categories');
                Route::get('products', [AdminProductController::class, 'index'])->name('products');
                Route::get('orders', [AdminOrderController::class, 'index'])->name('orders');
                Route::get('coupons', [CouponController::class, 'index'])->name('coupons');
                Route::get('banners', [BannerController::class, 'index'])->name('banners');
            });
    });
