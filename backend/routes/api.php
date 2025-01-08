<?php

use App\Http\Controllers\Api\UserController;
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

Route::get('/', function () {
    return response()->json([
        "Tudo Certo com sua API"
    ]);
});

Route::post('login', 'App\Http\Controllers\Api\UserController@login');
Route::get('login', 'App\Http\Controllers\Api\UserController@login')->name('login');
Route::get('logout', 'App\Http\Controllers\Api\UserController@logout');

Route::get('user/forgot-password', 'App\Http\Controllers\Api\UserController@forgotPasswordGet');
Route::post('user/forgot-password', 'App\Http\Controllers\Api\UserController@forgotPassword');
Route::get('user/reset-password', 'App\Http\Controllers\Api\UserController@resetPasswordGet');
Route::post('user/reset-password/{token}/{email}', 'App\Http\Controllers\Api\UserController@resetPassword');
Route::post('user/save-for-auth-social-media', 'App\Http\Controllers\Api\UserController@saveForAuthSocialMedia');
Route::get('user/create-simple', 'App\Http\Controllers\Api\UserController@createSimple');
Route::customResource('user', \App\Http\Controllers\Api\UserController::class);//->only(['create', 'store', 'uploadTemporary', 'removeTemporary']);

/**
 * auth with sanctum
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/refresh', 'App\Http\Controllers\Api\UserController@refreshToken');
    Route::get('user/refresh-token', 'App\Http\Controllers\Api\UserController@refreshToken');
    Route::get('user/check-login', 'App\Http\Controllers\Api\UserController@checkLogin');
    Route::get('user/profile', 'App\Http\Controllers\Api\UserController@profile');
    Route::get('user/verify-email/{url}', 'App\Http\Controllers\Api\EmailVerificationController@verify');
    Route::get('email/verification-notification', 'App\Http\Controllers\Api\EmailVerificationController@sendVerificationEmail');
    Route::get('verify-email/{id}/{hash}', 'App\Http\Controllers\Api\EmailVerificationController@verify')->name('verification.verify');
    //Route::customResource('user', \App\Http\Controllers\Api\UserController::class);//->except(['create', 'store']);
    //Route::customResource('user', \App\Http\Controllers\Api\UserController::class)->only(['show', 'list', 'all', 'search', 'update', 'destroy', 'forceDelete', 'restore']);   

    Route::get('message-read', 'App\Http\Controllers\Api\MessageController@messageRead');
    Route::get('message-not-read', 'App\Http\Controllers\Api\MessageController@messageNotRead');
    Route::get('message/read-all', 'App\Http\Controllers\Api\MessageController@readAll');
    Route::customResource('message', \App\Http\Controllers\Api\MessageController::class);

    Route::customResource('cnae', \App\Http\Controllers\Api\CnaeController::class);
    Route::customResource('user-cnae', \App\Http\Controllers\Api\UserCnaeController::class);

    Route::get('task/list-items-to-user-id/{user_id?}', '\App\Http\Controllers\Api\TaskController@listItemsToUserId');
    Route::customResource('task', \App\Http\Controllers\Api\TaskController::class); 

});


Route::any('reverb/auth', '\App\Http\Controllers\Api\BaseController@reverb');
//Route::any('broadcasting/auth', '\Illuminate\Broadcasting\BroadcastController@authenticate');

//https://todoist.com/pt-BR