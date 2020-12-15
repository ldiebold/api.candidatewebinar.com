<?php

use App\Http\Controllers\OnlineEventController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\OnlineEventUserController;
use App\Http\Controllers\TutorialVideoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('downlines', [UserController::class, 'downlines']);
    });

    Route::resources([
        'online_events' => OnlineEventController::class,
        'users' => UserController::class,
        'candidates' => CandidateController::class,
        'online_event_users' => OnlineEventUserController::class,
        'tutorial_videos' => TutorialVideoController::class
    ]);
});

Broadcast::routes(['middleware' => ['auth:sanctum']]);
