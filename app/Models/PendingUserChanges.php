<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingUserChanges extends Model
{
    protected $fillable = ["user_id", "url_key", "user_change_types_id", "user_change_statuses_id", "desired_value"];
    public function user() : BelongsTo{
        return $this->belongsTo(User::class);
    }
}
