<?php

use App\Http\Controllers\Api\Group\PlaylistController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\UserPlaylistController;
use App\Http\Controllers\IndexController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [IndexController::class, 'index']);

// User endpoints
Route::apiResource('playlists', UserPlaylistController::class);
Route::apiResource('groups', GroupController::class);

// Group endpoints
Route::group([
    'prefix' => '/group/{group}',
], function ()
{
    Route::apiResource('playlists', PlaylistController::class);
});
