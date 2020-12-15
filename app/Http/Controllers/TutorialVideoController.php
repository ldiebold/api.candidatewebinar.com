<?php

namespace App\Http\Controllers;

use App\Models\TutorialVideo;
use Illuminate\Http\Request;

class TutorialVideoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TutorialVideo::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->role === 'admin' || $request->user()->role === 'super admin') {
            return TutorialVideo::all();
        }

        return TutorialVideo::where('admin', false)->get();
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
            'title' => 'required'
        ]);

        return TutorialVideo::create($request->input());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TutorialVideo  $tutorialVideo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TutorialVideo $tutorialVideo)
    {
        $tutorialVideo->update($request->input());
        return $tutorialVideo;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TutorialVideo  $tutorialVideo
     * @return \Illuminate\Http\Response
     */
    public function destroy(TutorialVideo $tutorialVideo)
    {
        $tutorialVideo->users()->detach();
        $tutorialVideo->delete();
        return $tutorialVideo;
    }
}
