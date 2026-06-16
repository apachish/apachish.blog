<?php

namespace Apachish\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'blog_posts';
    /**
     * draft – only the author (and admins) sees it.
     * pending – submitted for review, visible to editors.
     * publish – public, respecting published_at (if in the future, it will become visible when that date arrives).
     * private – only logged‑in users with permission.
     * trash – soft‑deleted (use SoftDeletes also on Post to move to trash).
     */
    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'status', 'published_at', 'password', 'parent_id', 'template', 'user_id'];
    protected $dates = ['published_at', 'deleted_at'];

    // Author
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Revisions
    public function revisions(): HasMany
    {
        return $this->hasMany(PostRevision::class);
    }

    // Latest revision (helper)
    public function latestRevision(): HasOne
    {
        return $this->hasOne(PostRevision::class)->latest('id');
    }

    // All media (polymorphic, for thumbnail, header, gallery, etc.)
    public function media(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'mediable')
            ->withPivot('type', 'order')
            ->withTimestamps()
            ->orderBy('order');
    }

    // Specific media types: thumbnail, header
    public function thumbnail(): MorphToMany
    {
        return $this->media()->wherePivot('type', 'thumbnail');
    }

    public function header(): MorphToMany
    {
        return $this->media()->wherePivot('type', 'header');
    }

    // Categories
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }

    // Tags
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    // Comments
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Meta (key-value)
    public function meta(): HasMany
    {
        return $this->hasMany(PostMeta::class);
    }

    // Helpers
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    // Auto‑save a revision before content change
    public static function boot()
    {
        parent::boot();
        static::updating(function (Post $post) {
            if ($post->isDirty(['title', 'excerpt', 'content'])) {
                $revision = new PostRevision([
                    'user_id' => auth()->id() ?? $post->user_id,
                    'title'   => $post->getOriginal('title'),
                    'excerpt' => $post->getOriginal('excerpt'),
                    'content' => $post->getOriginal('content'),
                ]);
                $post->revisions()->save($revision);
            }
        });
    }

    // Retrieve the featured image URL easily
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail->first()?->url;
    }

    public function getHeaderUrlAttribute(): ?string
    {
        return $this->header->first()?->url;
    }
}
