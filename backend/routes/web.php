<?php

use App\Events\Example;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    broadcast(new Example(User::find(1),));
    return view('welcome');
});

Route::get('/broadcast', function () {
    broadcast(new Example(User::find(1),));
}); */


Route::get('message/migrate', 'App\Http\Controllers\Api\MessageController@migrate');