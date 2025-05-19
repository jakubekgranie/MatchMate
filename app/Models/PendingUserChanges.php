<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingUserChanges extends Model
{
    protected $fillable = ["user_id", "url_key", "user_change_types_id", "user_change_statuses_id", "desiredValue"];
}
