<?php

namespace Apachish\Blog\App\Observers;

use Apachish\Blog\App\Models\Post;
use Apachish\Blog\App\Models\PostRevision;

class BlogPostObserver
{
    public function updated(Post $post): void
    {

        if (($post->wasChanged('status') && $post->status === 'published') || $post->status === 'published') {
            $this->createRevision($post);
        }
    }

    protected function createRevision(Post $post): void
    {
        PostRevision::updateOrCreate([
            'post_id'          => $post->id,
            'user_id'          => $post->user_id,
            ],[
            'project_id'       => $post->project_id,
            'title'            => $post->title,
            'slug'            => $post->slug,
            'excerpt'          => $post->excerpt,
            'content'          => $post->content,
            'locale'          => $post->locale,
            'featured_image'   => $post->featured_image,
            'meta_title'       => $post->meta_title,
            'meta_description' => $post->meta_description,
            'published_at'     => $post->published_at ?? now(),
        ]);
    }
}
