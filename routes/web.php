<?php

use App\Http\Controllers\DarajaApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LidenController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::middleware(['auth'])->controller(DashboardController::class)->group(function () {
    Route::get('/', 'dashboard')->name('dashboard');
    Route::get('/players', 'players')->name('players');
    Route::get('/online/{index}', 'online')->name('online');
});
Route::get('/registerUrl', [DarajaApiController::class, 'registerUrl']);

// Route::get('/send-message', [LidenController::class, 'sendSMS']);

//b2c test routes
Route::get('/mpesa/b2c/{phone}/{amount}', [WithdrawalController::class, 'b2cPaymentRequest']);
