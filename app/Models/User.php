<?php

namespace App\Models;

use App\Traits\HasSchema;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasSchema;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'upline_id',
        'phone_number'
    ];

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

    public function hasDownline(User $user)
    {
        return $this->downlines->pluck('id')->contains($user->id);
    }

    public function hasUpline(User $user)
    {
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
     * Check the use is an admin
     *
     * @return boolean
     */
    public function isCandidate()
    {
        return !!($this->role === 'candidate');
    }
}
