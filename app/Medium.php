<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Medium extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'guid',
        'title',
        'description',
        'disk',
        'path',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (self $medium) {
            $medium->guid = (string) Str::uuid();
            $medium->user_id = Auth::id() ?? null;
        });
    }
}
