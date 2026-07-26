<?php

namespace Apachish\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $table = 'blog_tags';
    protected $fillable = ['name', 'slug','project_id','locale'];
}
