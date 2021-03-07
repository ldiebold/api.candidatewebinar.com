<?php

use App\Http\Controllers\OnlineEventController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\OnlineEventUserController;
use App\Http\Controllers\TutorialVideoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFeedbackController;
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

/**
 * Public Api Routes
 */
Route::get('online_events/uid/{uid}', [OnlineEventController::class, 'showByUid']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::post('update-profile-photo/{user}', [
            UserController::class,
            'updateProfilePhoto'
        ]);
        Route::get('downlines', [UserController::class, 'downlines']);
        Route::post('resend-password/{user}', [UserController::class, 'resendPassword']);
    });

    Route::resources([
        'online_events' => OnlineEventController::class,
        'users' => UserController::class,
        'candidates' => CandidateController::class,
        'online_event_users' => OnlineEventUserController::class,
        'tutorial_videos' => TutorialVideoController::class,
        'user_feedback' => UserFeedbackController::class
    ]);
});

Broadcast::routes(['middleware' => ['auth:sanctum']]);
