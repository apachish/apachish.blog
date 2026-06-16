<?php

namespace Apachish\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = ['disk', 'path', 'name', 'mime_type', 'size', 'meta'];
    protected $casts = ['meta' => 'array'];

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    // Morph to posts, pages, etc.
    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'mediable')->withPivot('type', 'order');
    }

    // You can add other morph types later (e.g., for page headers)
}
