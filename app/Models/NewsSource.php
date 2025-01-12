<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class NewsSource extends Model
{
    public function scopeActive(Builder $query) {

        return $query->where('is_active', 1);
    }
}
