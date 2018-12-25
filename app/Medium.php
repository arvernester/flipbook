<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Medium extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'guid',
        'title',
        'description',
        'disk',
        'image_path',
        'path',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $appends = [
        'image_full_path',
        'full_path',
        'contents',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (self $medium) {
            $medium->guid = (string) Str::uuid();
            $medium->user_id = Auth::id() ?? null;
        });
    }

    public function getImageFullPathAttribute(): ?string
    {
        if (!empty($this->attributes['image_path'])) {
            return url(Storage::url($this->attributes['image_path']));
        }

        return null;
    }

    public function getFullPathAttribute(): ?string
    {
        if (!empty($this->attributes['path'])) {
            return url(Storage::url($this->attributes['path']));
        }

        return null;
    }

    public function getContentsAttribute(): array
    {
        if (!empty($this->relations['pages'])) {
            return collect($this->relations['pages'])->mapWithKeys(function ($page) {
                return [$page->page => $page->title];
            })->toArray();
        }

        return [];
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
