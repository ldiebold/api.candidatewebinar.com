<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SendCandidateAccountPasswordNotification;
use App\Notifications\SendFullUserAccountPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        $query = User::query();

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
            'upline_id' => $request->user()->id,
            'password' => bcrypt($password)
        ]);

        if ($request->role === 'candidate') {
            $user = $request->user()->candidates()->create($request->input());
            if ($request->has('event_ids')) {
                $user->online_events()->attach($request->event_ids);
            }
            $user->notify(new SendCandidateAccountPasswordNotification($user, $password));

            return $user;
        }

        $user = User::create($request->input());
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
        $user->update($request->input());
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

        $userWithDescendants = User::select(['id', 'name', 'upline_id', 'role'])
            ->with('descendantsAndSelf:id,name,depth,path,upline_id,role')
            ->find($user->id);

        $descendantsArray = $userWithDescendants->descendantsAndSelf->toArray();
        $tree = buildTree($descendantsArray, $parentId = $user->id);

        $user->children = array_values($tree);

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
