<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'slug',
        'user_id',
    ];

    function user():BelongsTo {
        return $this->belongsTo(User::class);
    }

    function comments():HasMany {
        return $this->hasMany(Comment::class);
    }

   function likes():HasMany {
        return $this->hasMany(Like::class);
    }
}
