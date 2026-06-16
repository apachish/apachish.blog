<?php

namespace Apachish\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table = 'blog_categories';

    protected $fillable = ['name', 'slug','parent_id','description','order','status','project_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
