<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    protected $fillable = [
        'key',
        'title',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'robots',
    ];

    public static function findByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }
}
