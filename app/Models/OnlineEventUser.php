<?php

namespace App\Models;

use App\Traits\HasSchema;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineEventUser extends Model
{
    use HasFactory;
    use HasSchema;

    protected $fillable = [
        'user_id',
        'online_event_id'
    ];
}
