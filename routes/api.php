<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\Api\UserPlaylistController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', [IndexController::class, 'index']);
Route::get('/test/login', [IndexController::class, 'login']);

Route::apiResource('playlists', UserPlaylistController::class);
