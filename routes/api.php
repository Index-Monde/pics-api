<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\CollectionController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\AuthentificationController;

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
Route::apiResources([
    'collections' => CollectionController::class
]);
Route::apiResources([
    'comments' => CommentController::class
]);
Route::apiResources(['notifications'=> NotificationController::class]);
