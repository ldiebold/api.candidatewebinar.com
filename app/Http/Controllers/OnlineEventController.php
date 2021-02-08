<?php

namespace App\Http\Controllers;

use App\Models\OnlineEvent;
use Illuminate\Http\Request;

class OnlineEventController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(OnlineEvent::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = $request->user()->role;

        if ($role === 'candidate') {
            return $request->user()
                ->online_events()
                ->where('online_events.archived', false)
                ->get();
        }

        return OnlineEvent::where('online_events.archived', false)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'video_url' => 'required'
        ]);

        return OnlineEvent::create($request->input());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return \Illuminate\Http\Response
     */
    public function show(OnlineEvent $onlineEvent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return \Illuminate\Http\Response
     */
    public function edit(OnlineEvent $onlineEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OnlineEvent $onlineEvent)
    {
        $onlineEvent->update($request->input());
        return $onlineEvent;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OnlineEvent  $onlineEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(OnlineEvent $onlineEvent)
    {
        $onlineEvent->users()->detach();
        $onlineEvent->delete();
        return $onlineEvent;
    }
}
