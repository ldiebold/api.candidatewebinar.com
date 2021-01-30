<?php

namespace App\Models;

use App\Traits\HasSchema;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasSchema;
    use HasRecursiveRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'upline_id',
        'phone_number',
        'seen_introduction_video'
    ];

    /**
     * The key used in recursive queries to find
     * the parent user
     *
     * @return string
     */
    public function getParentKeyName()
    {
        return 'upline_id';
    }

    /**
     * Route notifications for the Nexmo channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification)
    {
        return $this->phone_number;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * A User has many candidates
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function candidates()
    {
        return $this->hasMany(\App\Models\User::class, 'upline_id')
            ->where('users.role', 'candidate');
    }

    public function online_events()
    {
        return $this->belongsToMany(\App\Models\OnlineEvent::class, 'online_event_users')
            ->withPivot(['id']);
    }

    /**
     * Check this user has the given user, downline
     *
     * @param \App\User|int $user
     * @return boolean
     */
    public function hasDownline($user)
    {
        $user = gettype($user) === 'integer' ? User::findOrFail($user) : $user;

        return $this->downlines()->pluck('id')->contains($user->id);
    }

    /**
     * Check this users upline is the given user
     *
     * @param \App\User|int $user
     * @return boolean
     */
    public function hasUpline(User $user)
    {
        $user = gettype($user) === 'integer' ? User::findOrFail($user) : $user;

        return $this->upline->id === $user->id;
    }

    /**
     * Check the use is an admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return !!($this->role === 'admin');
    }

    /**
     * Check the use is an IBO
     *
     * @return boolean
     */
    public function isIbo()
    {
        return !!($this->role === 'ibo');
    }

    /**
     * Check the use is a super admin
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return !!($this->role === 'super admin');
    }

    /**
     * Get all users this user has permission to index
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function indexableUsers()
    {
        $roleScopes = collect([
            'super admin' => static::query(),
            'admin' => $this->downlinesAndSelf(),
            'ibo' => $this->downlinesAndSelf(),
        ]);

        abort_if(!$roleScopes->has($this->role), 403);

        return $roleScopes[$this->role];
    }

    /**
     * Check the use is an admin
     *
     * @return boolean
     */
    public function isCandidate()
    {
        return !!($this->role === 'candidate');
    }

    /**
     * Get all of this users downlines
     *
     * @return \Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\Descendants
     */
    public function downlines()
    {
        return $this->descendants();
    }

    /**
     * Get all of this users downlines and self
     *
     * @return \Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\Descendants
     */
    public function downlinesAndSelf()
    {
        return $this->descendantsAndSelf();
    }

    /**
     * Change this users password
     *
     * @param string $newPassword
     * @return \App\Models\User
     */
    public function changePassword($newPassword)
    {
        $this->password = bcrypt($newPassword);
        $this->save();
        return $this;
    }
}
