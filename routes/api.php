<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\SocialController;
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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 */
Route::group([],function() {

    Route::post('login',[AuthController::class,'login'])->name('login');
    Route::post('register',[AuthController::class,'register']);

    Route::apiResource('listings',ListingController::class)->only('show','index');
    Route::post('getLandlordData',[ListingController::class,'getLandlordData']);

    Route::get('login/google', [SocialController::class, 'redirect'])->name('redirect');
    Route::get('login/google/callback', [SocialController::class, 'callback'])->name('callback');
});

Route::group(['middleware' => 'auth:sanctum'],function() {
    

    Route::apiResource('listings',ListingController::class)->except('index','show');
    Route::get('listings/{listing}/edit',[ListingController::class,'edit']);
    Route::get('profile',[AuthController::class,'profile']);
    Route::put('update_profile',[AuthController::class,'updateProfile']);
    Route::post('logout',[AuthController::class,'logout']);


});