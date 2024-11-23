<?php

use App\Http\Controllers\DarajaApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnfonSmsController;
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
    Route::get('/winners', 'winners')->name('winners');
    Route::get('/online/{index}', 'online')->name('online');
    Route::get('/radios', 'radios')->name('radios');
    Route::post('/radios/create', 'create_radio')->name('create_radio');

    Route::prefix('/mpesa')->group(function () {
        Route::get('/paybills', 'paybills')->name('paybills');
        Route::post('/paybills', 'create_paybill')->name('create_paybill');

        Route::get('/b2cs', 'b2cs')->name('b2cs');
        Route::post('/b2cs', 'create_b2c')->name('create_b2c');
    });

    Route::prefix('/sms')->group(function () {
        Route::get('/incomings', 'incomings')->name('incomings');
        Route::post('/incomings', 'create_incoming')->name('create_incoming');

        Route::get('/outgoings', 'outgoings')->name('outgoings');
        Route::post('/outgoings', 'create_outgoing')->name('create_outgoing');
    });

    Route::prefix('/platforms')->group(function () {
        Route::get('/', 'platforms')->name('platforms');
        Route::post('/', 'create_platform')->name('create_platform');
    });

    Route::post('/filter', 'filter')->name('filter');
});
Route::get('/registerUrl', [DarajaApiController::class, 'registerUrl']);

// Route::get('/send-message/{message}/{phone}/{shortcode}', [OnfonSmsController::class, 'sendSMS']);

//b2c test routes
Route::get('/mpesa/b2c/{phone}/{amount}/{platform}', [WithdrawalController::class, 'b2cPaymentRequest']);
