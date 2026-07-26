<?php

namespace Apachish\Blog\App\Models;

use Apachish\Blog\App\Traits\HasReadingTime;
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
    use HasReadingTime;

    protected $readingTimeColumn = 'content'; // اختیاری، اگر نام فیلد فرق دارد

    protected $table = 'blog_posts';
    /**
     * draft – only the author (and admins) sees it.
     * pending – submitted for review, visible to editors.
     * publish – public, respecting published_at (if in the future, it will become visible when that date arrives).
     * private – only logged‑in users with permission.
     * trash – soft‑deleted (use SoftDeletes also on Post to move to trash).
     */
    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'status', 'published_at',
        'user_id','project_id','locale','featured_image',
        'views_count', 'unique_views_count','meta_title','meta_description',
        'estimated_reading_time', 'average_reading_time',
        'comments_count', 'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'unique_views_count' => 'integer',
        'estimated_reading_time' => 'integer',
        'average_reading_time' => 'integer',
    ];

    protected $dates = ['published_at', 'deleted_at'];

    public function views()
    {
        return $this->hasMany(BlogPostView::class, 'post_id');
    }

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
        return $this->belongsToMany(Category::class, 'blog_category_post');
    }

    // Tags
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_post_tag')->withTimestamps();
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
//        static::updating(function (Post $post) {
//            if ($post->isDirty(['title', 'excerpt', 'content'])) {
//                $revision = new PostRevision([
//                    'user_id' => auth()->id() ?? $post->user_id,
//                    'title'   => $post->getOriginal('title'),
//                    'excerpt' => $post->getOriginal('excerpt'),
//                    'project_id' => $post->getOriginal('project_id'),
//                    'content' => $post->getOriginal('content'),
//                ]);
//                $post->revisions()->save($revision);
//            }
//        });
    }

    // محاسبه زمان مطالعه (بر اساس تعداد کلمات)
    public function calculateEstimatedReadingTime()
    {
        $wordsPerMinute = 200; // میانگین سرعت خواندن فارسی
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / $wordsPerMinute);

        $this->update(['estimated_reading_time' => $minutes]);

        return $minutes;
    }

    // محاسبه میانگین زمان واقعی خواندن
    public function updateAverageReadingTime()
    {
        $average = $this->views()
            ->whereNotNull('reading_time')
            ->where('reading_time', '>', 10) // حداقل 10 ثانیه
            ->avg('reading_time');

        if ($average) {
            $this->update(['average_reading_time' => (int) $average]);
        }

        return $average;
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
