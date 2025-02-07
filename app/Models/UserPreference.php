<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'preference_type',
        'preference_value',
        'user_id',
    ];
}
