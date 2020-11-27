<?php

namespace App\Http\Controllers;

use App\Models\OnlineEvent;
use App\Models\OnlineEventUser;
use App\Models\User;
use App\Notifications\CandidateAssignedToOnlineEventNotification;
use Illuminate\Http\Request;

class OnlineEventUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::find($request->user_id);
        $onlineEvent = OnlineEvent::find($request->online_event_id);

        $onlineEventUserQuery = OnlineEventUser::where('user_id', $request->user_id)
            ->where('online_event_id', $request->online_event_id)
            ->delete();

        return OnlineEventUser::create($request->input());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OnlineEventUser  $onlineEventUser
     * @return \Illuminate\Http\Response
     */
    public function show(OnlineEventUser $onlineEventUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OnlineEventUser  $onlineEventUser
     * @return \Illuminate\Http\Response
     */
    public function edit(OnlineEventUser $onlineEventUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OnlineEventUser  $onlineEventUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OnlineEventUser $onlineEventUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OnlineEventUser  $onlineEventUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(OnlineEventUser $onlineEventUser)
    {
        $onlineEventUser->delete();
        return $onlineEventUser;
    }
}
