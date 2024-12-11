<?php

use App\Http\Controllers\MOSmsController;
use App\Http\Controllers\MPESAResponseController;
use App\Http\Controllers\USSDController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/c2b/confirmation', [MPESAResponseController::class, 'confirmation']);
Route::post('/c2b/validation', [MPESAResponseController::class, 'validation']);
Route::post('/c2b/express', [MPESAResponseController::class, 'express']);

Route::post('/MO/ussd', [USSDController::class, 'handle']); //handles onfon836
// Route::post('/MO/ussd/245', [USSDController::class, 'handle245']); //handles onfon
Route::post('/MO/ussd/245', [USSDController::class, 'proxyRequest']); //handles onfon
//feltonsms
// Route::post('/MO/ussd/437', [USSDController::class, 'handle245']); //handles onfon
Route::post('/MO/ussd/437', function (Request $request) {
    Log::info($request->all());

    return 'END Accepted for Processing';
});

Route::post('/MO/sms/v2', [MOSmsController::class, 'handlev2']); //handles onfon

//other providers

Route::post('/MO/sms', [MOSmsController::class, 'handlev2']); //handles bulk ke
Route::post('/MO/ussd/v2', [USSDController::class, 'handle']);

// b2c routes
Route::post('/b2c/result', [WithdrawalController::class, 'handleResult']);
