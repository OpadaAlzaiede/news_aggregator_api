<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    public function scopeActive(Builder $query)
    {

        return $query->where('is_active', 1);
    }
}
