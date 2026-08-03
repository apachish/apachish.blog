<?php

namespace Apachish\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'blog_comments';
    protected $fillable = ["post_id","user_id","parent_id","content","status",
        "author_name","author_email","author_ip","status"];

    public function replies()
    {
        return $this->hasMany(Comment::class, "parent_id");
    }

    public function post()
    {
        return $this->hasMany(PostRevision::class, "parent_id");
    }
}
