<?php

namespace Apachish\Blog\App\Models;

use Apachish\Blog\App\Traits\HasReadingTime;
use Apachish\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostRevision extends Model
{
    use HasReadingTime;

    protected $readingTimeColumn = 'content'; // اختیاری، اگر نام فیلد فرق دارد

    protected $table = 'blog_post_revisions';
    protected $fillable = ['post_id', 'user_id', "project_id",'title','slug', 'excerpt', 'content', 'meta_title','meta_description','featured_image','locale','published_at'];
    protected $casts = ['meta' => 'array'];

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
