<?php

use App\Http\Controllers\MOSmsController;
use App\Http\Controllers\MPESAResponseController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Http\Request;
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

Route::post('/MO/sms', [MOSmsController::class, 'handle']);

// b2c routes
Route::post('/b2c/result', [WithdrawalController::class, 'handleResult']);
