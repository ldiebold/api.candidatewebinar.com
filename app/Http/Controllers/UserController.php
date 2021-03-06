<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SendCandidateAccountPasswordNotification;
use App\Notifications\SendFullUserAccountPasswordNotification;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->user()->indexableUsers();

        if ($request->has('with.online_events')) {
            $query->with('online_events');
        }

        return $query->get();
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
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users'
        ]);

        $password = Str::random(8);
        $request->merge([
            'upline_id' => $request->has('upline_id') ? $request->upline_id : $request->user()->id
        ]);

        if ($request->role === 'candidate') {
            $user = $request->user()->candidates()->make($request->input());
            $user->password = bcrypt($password);
            $user->save();
            if ($request->has('event_ids')) {
                $user->online_events()->attach($request->event_ids);
            }
            $user->notify(new SendCandidateAccountPasswordNotification($user, $password));

            return $user;
        }

        $user = User::make($request->input());
        $user->password = bcrypt($password);
        $user->save();
        $user->notify(new SendFullUserAccountPasswordNotification($user, $password));

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Handle Password Change
        if ($request->has('new_password')) {
            $request->validate([
                'current_password' => 'password',
                'new_password' => 'required|confirmed'
            ]);

            $user->changePassword($request->new_password);
            return $user;
        }

        $user->update($request->input());
        return $user;
    }

    public function updateProfilePhoto(Request $request, User $user)
    {
        // Handle Profile Photo Change
        if ($request->file) {
            $request->validate([
                'file' => 'image|max:1024'
            ]);

            $user->updateProfilePhoto($request->file);
        }

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $user;
    }

    /**
     * Display all of a given users downlines.
     *
     * @return \Illuminate\Http\Response
     */
    public function downlines(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'candidate') {
            abort(401);
        }

        $userWithDescendants = User::query()
            ->with('descendantsAndSelf:id,name,depth,path,upline_id,role')
            ->find($user->id);

        $descendantsArray = $userWithDescendants->descendantsAndSelf->toArray();
        $tree = buildTree($descendantsArray, $parentId = $user->id);

        $user->children = array_values($tree);

        return $user;
    }

    public function resendPassword(User $user, Request $request)
    {
        $this->authorize('update', $user);

        abort_unless($user->upline, 422, 'the given user must have an upline');
        abort_unless($user->isCandidate(), 422, 'only candidates can have their password reset');

        $password = Str::random(8);
        $user->password = bcrypt($password);
        $user->save();
        $user->notify(new SendCandidateAccountPasswordNotification($user, $password));

        return $user;
    }
}

function buildTree(array &$elements, $parentId = 0)
{
    $branch = array();

    foreach ($elements as &$element) {

        if ($element['upline_id'] == $parentId) {
            $children = array_values(buildTree($elements, $element['id']));
            if ($children) {
                $element['children'] = $children;
            }
            $branch[$element['id']] = $element;
            unset($element);
        }
    }
    return $branch;
}
