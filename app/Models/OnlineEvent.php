<?php

namespace App\Models;

use App\Traits\HasSchema;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineEvent extends Model
{
    use HasFactory;
    use HasSchema;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'automated',
        'hex_color',
        'video_url',
        'email_notifications_sent',
        'archived'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'archived' => 'boolean'
    ];

    /**
     * An OnlineEvent has many Users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'online_event_users');
    }

    /**
     * Archive this events
     *
     * @return boolean
     */
    public function archive()
    {
        $this->update(['archived' => true]);
    }

    /**
     * Scope by online events that have not started yet
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasNotStarted($query)
    {
        $now = Carbon::now();
        return $query->where('start_time', '>', $now);
    }

    /**
     * Scope by online events that have started
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasStarted($query)
    {
        $now = Carbon::now();
        return $query->where('start_time', '<', $now);
    }

    /**
     * Scope by online events that have not ended yet
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasNotEnded($query)
    {
        $now = Carbon::now();
        return $query->where('end_time', '>', $now);
    }

    /**
     * Scope by online events that have ended
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasEnded($query)
    {
        $now = Carbon::now();
        return $query->where('end_time', '<', $now);
    }

    /**
     * Scope by online events that are currently playing
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsPlaying($query)
    {
        return $query->hasStarted()->hasNotEnded();
    }

    /**
     * Scope Online Events that start in x minutes
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartsInXMinutes($query, int $minutes)
    {
        return $query->where('start_time', ">", now()->addMinutes($minutes - 1))
            ->where('start_time', "<", now()->addMinutes($minutes));
    }

    /**
     * Scope Online Events where "now" is between x minutes before,
     * and the start time
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartsBetweenNowAndXMinutesBeforeStartTime($query, int $minutes)
    {
        return $query
            ->where('start_time', ">", now())
            ->where('start_time', "<", now()->addMinutes($minutes));
    }

    /**
     * Scope Online Events that end in x minutes
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEndsInXMinutes($query, int $minutes)
    {
        return $query->where('end_time', ">", now()->addMinutes($minutes - 1))
            ->where('end_time', "<", now()->addMinutes($minutes));
    }

    public function scopeEndedXMinutesAgo($query, int $minutes)
    {
        return $query->where('end_time', ">", now()->subMinutes($minutes + 1))
            ->where('end_time', "<", now()->subMinutes($minutes));
    }

    public function scopeEndedMoreThanXMinutesAgo($query, int $minutes)
    {
        return $query->where('end_time', "<", now()->subMinutes($minutes));
    }

    /**
     * Scope to Online Events
     *
     * @param [type] $query
     * @param integer $minutes
     * @return void
     */
    public function scopeStartedXMinutesAgo($query, int $minutes)
    {
        return $query->where('start_time', ">", now()->subMinutes($minutes + 1))
            ->where('start_time', "<", now()->subMinutes($minutes));
    }
}
