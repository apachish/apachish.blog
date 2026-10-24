<?php

namespace Apachish\Blog\App\Observers;

use Apachish\Blog\App\Models\Post;
use Apachish\Blog\App\Models\PostRevision;

class BlogPostObserver
{
    /**
     * هنگام ساخت مقاله جدید (اگر مستقیم پابلیش شد)
     */
    public function created(Post $post): void
    {
        if ($post->status === 'published') {
            $this->createOrUpdateRevision($post);
        }
    }

    /**
     * هنگام بروزرسانی مقاله
     */
    public function updated(Post $post): void
    {
        // ۱. اگر مقاله به حالت پیش‌نویس (draft) یا زباله‌دان (trash) رفت، نسخه عمومی آن را پاک کن
        if (in_array($post->status, ['draft', 'trash', 'pending'])) {
            $post->revisions()->delete();
            return;
        }

        // ۲. اگر وضعیت مقاله published است، نسخه منتشرشده را بروزرسانی کن
        if ($post->status === 'published') {
            $this->createOrUpdateRevision($post);
        }
    }

    /**
     * ایجاد یا بروزرسانی آخرین نسخه منتشرشده
     */
    protected function createOrUpdateRevision(Post $post): void
    {
        // با updateOrCreate همیشه آخرین نسخه منتشرشده در جدول revisions به روز می‌ماند
        PostRevision::updateOrCreate(
            ['post_id' => $post->id],
            [
                'user_id'          => $post->user_id,
                'project_id'       => $post->project_id,
                'title'            => $post->title,
                'slug'             => $post->slug,
                'excerpt'          => $post->excerpt,
                'content'          => $post->content,
                'locale'           => $post->locale,
                'featured_image'   => $post->featured_image,
                'meta_title'       => $post->meta_title,
                'meta_description' => $post->meta_description,
                'published_at'     => $post->published_at ?? now(),
            ]
        );
    }

    /**
     * هنگام SoftDelete شدن یا حذف کامل پست
     */
    public function deleted(Post $post): void
    {
        $post->revisions()->delete();
    }
}
