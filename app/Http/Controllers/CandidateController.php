<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::where('upline_id', $request->user()->id)
            ->where('role', 'candidate');

        $request->whenHas('with', function ($with) use ($query) {
            if (in_array('online_events', $with)) {
                $query->with('online_events');
            }
        });

        return $query->get();
    }
}
