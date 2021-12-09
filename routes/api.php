<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\XanpoolApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/xanpool/signup',                          [XanpoolApiController::class, 'signup']);
Route::post('/xanpool/phone/verify',                    [XanpoolApiController::class, 'requestPhoneVerification']);
Route::post('/xanpool/phone/verify/complete',           [XanpoolApiController::class, 'completePhoneVerification']);
Route::post('/xanpool/kyc/upload',                      [XanpoolApiController::class, 'uploadKycDoc']);
Route::post('/xanpool/kyc/request',                     [XanpoolApiController::class, 'kycRequest']);
Route::get('/xanpool/cryptos',                          [XanpoolApiController::class, 'supportedCryptos']);
Route::get('/xanpool/limits',                           [XanpoolApiController::class, 'limits']);
Route::post('/xanpool/prices',                          [XanpoolApiController::class, 'prices']);
Route::post('/xanpool/estimateCost',                    [XanpoolApiController::class, 'estimateCost']);
Route::post('/xanpool/transaction/create',              [XanpoolApiController::class, 'createTransaction']);
Route::post('/xanpool/transaction/cancel',              [XanpoolApiController::class, 'cancelTransaction']);
Route::post('/xanpool/hook',                            [XanpoolApiController::class, 'hook']);
