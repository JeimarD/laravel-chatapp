<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Broadcast;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Broadcast::routes(['middleware' => ['auth:api']]);

Route::group(['middleware' => ['auth:api']], function ($router) {
    Route::post('chat/create', [ChatController::class, 'create'])->name('chat.create');
    Route::get('/users', [UserController::class, 'index']);
    Route::post('message/send', [MessageController::class, 'sent']);
    Route::get('chat/{chat}/get-messages', [ChatController::class, 'getMessages']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('/users/online', [UserController::class, 'connected']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('/authorize/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider'])->name('api.social.redirect');
Route::get('/authorize/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('api.social.callback');