<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth')->group(function() {
	Route::get('/currentUser', [UserController::class, 'currentUser']);

	Route::get('/chats', [ChatController::class, 'index']);
	Route::get('/chats/{chat}', [ChatController::class, 'show']);
	Route::post('/chats/{chat}/markAsRead', [ChatController::class, 'markAsRead']);

	Route::get('/contacts', [ContactController::class, 'index']);
	Route::post('/contacts', [ContactController::class, 'store']);

	Route::get('/profile/{user}', [ProfileController::class, 'show']);
	
	Route::get('/chats/{chat}/messages', [MessageController::class, 'index']);
	Route::post('/personal_messages/{recipient}', [MessageController::class, 'store']);
	
	Route::get('/users', [UserController::class, 'index']);
	Route::get('/users/{user}', [UserController::class, 'show']);
	Route::post('/updateProfile', [UserController::class, 'update']);
	Route::post('/removeProfilePhoto', [UserController::class, 'removeProfilePhoto']);
});