<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'cover_image',
        'pinned',
        'user_id'
    ];
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function tags():BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
    public function scopePinnedFirst($query)
    {
        return $query->orderBy('pinned', 'desc');
    }
    public function getCoverImageUrlAttribute()
    {
        return asset('storage/' . $this->cover_image);
    }
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('stats');
        });

        static::deleted(function () {
            Cache::forget('stats');
        });
    }
}

