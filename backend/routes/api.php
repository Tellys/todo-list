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
Route::resource('user', UserController::class)->only(['create', 'store']);

Route::get('webhook/config', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixWebhookController@config');
Route::any('webhook/receiver/{key?}', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixWebhookController@receiver');
Route::get('webhook/detail/{chave?}', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixWebhookController@detail');
Route::get('webhook/list', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixWebhookController@list');
Route::get('webhook/delete', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixWebhookController@delete');
Route::customResource('webhook', \App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixWebhookController::class); 

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
    Route::customResource('user', UserController::class)->except(['create', 'store']);

    Route::get('message-read', 'App\Http\Controllers\Api\MessageController@messageRead');
    Route::get('message-not-read', 'App\Http\Controllers\Api\MessageController@messageNotRead');
    Route::get('message/read-all', 'App\Http\Controllers\Api\MessageController@readAll');
    Route::customResource('message', MessageController::class);

    Route::get('tennis-court/create-simple', 'App\Http\Controllers\Api\TennisCourtController@createSimple');
    Route::post('tennis-court/search-advanced/{search?}', 'App\Http\Controllers\Api\TennisCourtController@searchAdvanced');
    Route::post('tennis-court/close-to-me/{search?}', 'App\Http\Controllers\Api\TennisCourtController@closeToMe');
    Route::post('tennis-court/filter-items/{search?}', 'App\Http\Controllers\Api\TennisCourtController@filterItems');
    Route::get('tennis-court/registration-phases', 'App\Http\Controllers\Api\TennisCourtController@registrationPhases');
    Route::customResource('tennis-court', TennisCourtController::class);

    Route::customResource('cnae', CnaeController::class);
    Route::customResource('user-cnae', UserCnaeController::class);
    Route::customResource('tennis-court-description', TennisCourtDescriptionController::class);
    Route::customResource('tennis-court-description-table', TennisCourtDescriptionTableController::class);
    Route::customResource('tennis-court-group', TennisCourtGroupController::class);
    Route::customResource('tennis-court-type', TennisCourtTypeController::class);

    Route::get('tennis-court-calendar/test', 'App\Http\Controllers\Api\TennisCourtCalendarController@test');
    Route::get('tennis-court-calendar/list-items-to-tennis-court-id/{id?}', 'App\Http\Controllers\Api\TennisCourtCalendarController@listItemsToTennisCourtId');
    Route::get('tennis-court-calendar/check-date-is-enable/{tennis_court_id}/{date?}', 'App\Http\Controllers\Api\TennisCourtCalendarController@checkDateIsEnable');
    Route::get('tennis-court-calendar/check-date-is-free/{tennis_court_id}/{date?}', 'App\Http\Controllers\Api\TennisCourtCalendarController@checkDateIsFree');
    Route::customResource('tennis-court-calendar', TennisCourtCalendarController::class);
    Route::customResource('location', LocationController::class);
    
    
    Route::get('tennis-court-media/list-images-to-item/{id?}', 'App\Http\Controllers\Api\TennisCourtMediaController@listImagesToItem');    
    Route::customResource('tennis-court-media', TennisCourtMediaController::class);

    Route::get('tennis-court-opening-hour/list-items-to-tennis-court-id/{id?}', 'App\Http\Controllers\Api\TennisCourtOpeningHourController@listItemsToTennisCourtId');    
    Route::get('tennis-court-opening-hour/number-of-hours-per-day/{tennis_court_id?}', 'App\Http\Controllers\Api\TennisCourtOpeningHourController@numberOfHoursPerDay');
    Route::customResource('tennis-court-opening-hour', TennisCourtOpeningHourController::class);

    Route::customResource('product-department', ProductDepartmentController::class);

    Route::customResource('products-default', ProductsDefaultController::class);

    Route::get('product/list-items-to-tennis-court-id/{id?}', 'App\Http\Controllers\Api\ProductController@listItemsToTennisCourtId');    
    Route::customResource('product', ProductController::class);

    Route::customResource('discount-policy', DiscountPolicyController::class);

    Route::get('tennis-court-involvement/all-for-user-logged', 'App\Http\Controllers\Api\TennisCourtInvolvementController@getAllForUserLogged');    
    Route::post('tennis-court-involvement/update-or-create', 'App\Http\Controllers\Api\TennisCourtInvolvementController@updateOrCreate');    
    Route::customResource('tennis-court-involvement', TennisCourtInvolvementController::class);

    Route::customResource('tennis-court-involvement-table', TennisCourtInvolvementTableController::class);

    Route::get('cart/set-is-expired', 'App\Http\Controllers\Api\CartController@setIsExpired');
    Route::post('cart/pay', 'App\Http\Controllers\Api\CartController@pay');
    Route::get('cart/all', 'App\Http\Controllers\Api\CartController@all');
    Route::post('cart/add-item-tennis-court', 'App\Http\Controllers\Api\CartController@addItemTennisCourt');
    Route::get('cart/confirm-price-and-stock', 'App\Http\Controllers\Api\CartController@confirmPriceAndStock');
    Route::customResource('cart', CartController::class);

    Route::get('customer-request/new/{client_id}', 'App\Http\Controllers\Api\CustomerRequestController@new');
    Route::get('customer-request/all/{returnJson?}', 'App\Http\Controllers\Api\CustomerRequestController@all');
    Route::get('customer-request/current', 'App\Http\Controllers\Api\CustomerRequestController@current');
    Route::customResource('customer-request', CustomerRequestController::class);

    Route::get('payment-method/pix/{total_price}', 'App\Http\Controllers\Api\PaymentMethodController@pix');
    //Route::get('payment-method/pix/{id}', 'App\Http\Controllers\Api\PaymentMethodController@pixShow');
    Route::post('payment-method/cartao-de-credito', 'App\Http\Controllers\Api\PaymentMethodController@cartaoDeCredito');
    Route::post('payment-method/boleto', 'App\Http\Controllers\Api\PaymentMethodController@boleto');
    Route::get('payment-method/boleto/{id}', 'App\Http\Controllers\Api\PaymentMethodController@boletoShow');
    Route::customResource('payment-method', PaymentMethodController::class); 

    Route::customResource('payment-efi-pay', \App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayController::class); 

    Route::get('payment-efi-pay-pix-cob/check-payment-is-made/{txid}', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixCobController@checkPaymentIsMade');
    Route::get('payment-efi-pay-pix-cob/pix-detail-charge/{txid}', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixCobController@pixDetailCharge');
    Route::customResource('payment-efi-pay-pix-cob', \App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixCobController::class); 
    

    Route::get('payment-efi-pay-pix-key/create', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixKeyController@create');
    Route::get('payment-efi-pay-pix-key/list', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixKeyController@list');
    Route::get('payment-efi-pay-pix-key/exists', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixKeyController@exists');
    Route::get('payment-efi-pay-pix-key/get', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixKeyController@getMyPixKey');
    Route::delete('payment-efi-pay-pix-key/delete', 'App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixKeyController@delete');
    Route::customResource('payment-efi-pay-pix-key', \App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixKeyController::class); 

    Route::get('payment-efi-pay-pix-webhook/check-payment-is-made/{txid}', '\App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixWebhookController@checkPaymentIsMade');
    Route::customResource('payment-efi-pay-pix-webhook', \App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixWebhookController::class); 


    //api/broadcasting/auth
});

Route::customResource('task', \App\Http\Controllers\Api\TaskController::class); 


Route::any('reverb/auth', '\App\Http\Controllers\Api\BaseController@reverb');
//Route::any('broadcasting/auth', '\Illuminate\Broadcasting\BroadcastController@authenticate');

//https://stackoverflow.com/questions/78648399/laravel-reverb-not-broadcasting-rest-api-with-docker

//https://todoist.com/pt-BR