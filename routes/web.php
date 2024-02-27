<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

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

Route::controller(WelcomeController::class)->group(function () {
    Route::get('/', 'index')->name('welcome');
    Route::get('/about', 'index')->name('about');
    Route::get('/contact', 'index')->name('contact');
});

Route::controller(OAuthController::class)->group(function () {
    Route::get('auth/{provider}/redirect', 'redirectToProvider')->name('auth.provider.redirect');
    Route::get('auth/{provider}/callback', 'handleProviderCallback')->name('auth.provider.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('wallets', WalletController::class)->only(['index', 'store']);

    Route::resource('categories', CategoryController::class)->only(['index']);

    Route::resource('transactions', TransactionController::class)->only(['index', 'store']);

    Route::resource('settings', SettingController::class)->only(['index']);
});

require __DIR__ . '/auth.php';
