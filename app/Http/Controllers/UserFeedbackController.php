<?php

namespace App\Http\Controllers;

use App\Models\UserFeedback;
use Illuminate\Http\Request;

class UserFeedbackController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(UserFeedback::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserFeedback::all();
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
            'category' => 'required|in:bug,idea,other',
            'message' => 'required|min:5'
        ]);

        $userFeedback = UserFeedback::make($request->input());
        $userFeedback->user_id = $request->user()->id;
        $userFeedback->save();

        return $userFeedback;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserFeedback  $userFeedback
     * @return \Illuminate\Http\Response
     */
    public function show(UserFeedback $userFeedback)
    {
        return $userFeedback;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserFeedback  $userFeedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserFeedback $userFeedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserFeedback  $userFeedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserFeedback $userFeedback)
    {
        $userFeedback->delete();
    }
}
