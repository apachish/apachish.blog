<?php

namespace Apachish\Blog\App\Models;

use Apachish\Blog\App\Traits\HasReadingTime;
use Apachish\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

class PostRevision extends Model implements Sitemapable
{
    use HasReadingTime;

    protected $readingTimeColumn = 'content'; // اختیاری، اگر نام فیلد فرق دارد

    protected $table = 'blog_post_revisions';
    protected $fillable = ['post_id', 'user_id', "project_id",'title','slug', 'excerpt', 'content', 'meta_title','meta_description','featured_image','locale','published_at'];
    protected $casts = ['meta' => 'array'];

    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('posts.show', $this->slug))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Comments
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class,'post_id');
    }
}
