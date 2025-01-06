<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

/* Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
*/

Broadcast::channel('chat', function () {
    return true;
});

//  Broadcast::channel('chat.{token}', function ($token) {
//     //return (new BaseController())->userToToken($token);
//     return true;
//  });

//Broadcast::channel('payment-pix-is-pay.{token}', function ($token) {
Broadcast::channel('payment-pix-is-pay.{id}', function ($user, $id) {
    //return (new BaseController())->userToToken($token);
    //return (int) $user->id === (int) $id;
    return true;
 }); 

 /**
  * parei aq. nao consigo fazer o private channel
  */