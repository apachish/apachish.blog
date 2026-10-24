<?php

namespace Apachish\Blog\App\Models;

use Apachish\Blog\App\Traits\HasReadingTime;
use Apachish\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

class PostRevision extends Model implements Sitemapable
{
    use HasReadingTime;
    use SoftDeletes;

    protected $readingTimeColumn = 'content'; // اختیاری، اگر نام فیلد فرق دارد

    protected $table = 'blog_post_revisions';
    protected $fillable = ['post_id', 'user_id', "project_id",'title','slug', 'excerpt', 'content', 'meta_title','meta_description','featured_image','locale','published_at'];
    protected $casts = ['meta' => 'array'];

    protected static function booted()
    {
        static::addGlobalScope('project_and_locale', function (Builder $builder) {
            $project = current_project();
            $locale = app()->getLocale();

            $projectId = data_get($project, 'id');

            // استفاده از نام جدول مدل به صورت امن و پویا
            $table = $builder->getModel()->getTable();

            if ($locale) {
                $builder->where("{$table}.locale", $locale);
            }

            if ($projectId) {
                $builder->where("{$table}.project_id", $projectId);
            }
        });

        static::creating(function ($category) {
            $project = current_project();
            $locale = app()->getLocale();

            // اگر locale مقدار نداشت، از زبان جاری اپلیکیشن پر شود
            if (empty($category->locale)) {
                $category->locale = $locale;
            }

            // اگر project_id مقدار نداشت، از تابع current_project پر شود
            if (empty($category->project_id)) {
                $category->project_id = data_get($project, 'id');
            }
        });
    }

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
