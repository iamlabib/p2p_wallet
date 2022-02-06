<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    

    Route::get('/transaction', [TransactionController::class, 'index']);    
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'transaction'

], function ($router) {
    Route::post('/send', [TransactionController::class, 'send'])->name('transaction.send');    
    Route::get('/rate/{from}/{to}', [TransactionController::class, 'getRate'])->name('conversion.rate');    
});