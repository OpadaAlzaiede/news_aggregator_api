<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'category',
        'source',
        'author',
        'published_at',
    ];

    public static function boot()
    {

        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    public function getRouteKeyName()
    {

        return 'slug';
    }
}
