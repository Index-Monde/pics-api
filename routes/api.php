<?php

use App\Http\Controllers\API\AuthentificationController;
use App\Http\Controllers\API\UserController;
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

Route::controller(AuthentificationController::class)->prefix('auth')->group(
    function () {
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
        Route::post('forgot-password', 'forgotPassword')->name('forgot-password');
        Route::post('reset-password', 'resetPassword')->name('reset-password');
    }
);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthentificationController::class, 'logout']);
    Route::put('update-profile', [UserController::class, 'updateProfileInformation']);
    Route::put('update-password', [UserController::class, 'updatePassword']);
});
Route::apiResources([
    'users' => UserController::class
]);
