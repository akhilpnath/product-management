<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/payment/success', [HomeController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/failed', [HomeController::class, 'paymentFailed'])->name('payment.failed');


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('brands', BrandController::class);
    
    Route::resource('products', ProductController::class)->except('show');
    
    Route::get('/products-import', [ProductController::class, 'importForm'])->name('products.import.form');
    Route::post('/products-import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/products-export', [ProductController::class, 'export'])->name('products.export');
    
    Route::post('/products/{product}/payment', [ProductController::class, 'createPayment'])->name('products.payment');
    Route::post('/payment/verify', [ProductController::class, 'verifyPayment'])->name('payment.verify');
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
