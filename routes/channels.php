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

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('App.Models.User.{upline_id}.Online.Event.{online_event_id}', function ($user, $upline_id, $online_event_id) {
    if (
        $user->role !== 'candidate' ||
        ($user->role === 'candidate' && $user->upline_id == $upline_id)
    ) {
        return [
            'id' => $user->id,
            'inOnlineEvent' => true
        ];
    }
});
