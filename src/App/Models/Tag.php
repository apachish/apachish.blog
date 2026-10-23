<?php

namespace Apachish\Blog\App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $table = 'blog_tags';
    protected $fillable = ['name', 'slug','project_id','locale'];

    protected static function booted()
    {
        $project = current_project();
        $locale = app()->getLocale() ;

        static::addGlobalScope('project_and_locale', function (Builder $builder) use ($project, $locale) {

            // بسته به معماری پروژه خودتان می‌توانید این مقدار را تنظیم کنید
            $projectId = data_get($project, 'id');
            $table = $builder->getModel()->getTable();

            if ($locale) {
                $builder->where("{$table}.locale", $locale);
            }

            if ($projectId) {
                $builder->where("{$table}.project_id", $projectId);
            }
        });
        static::creating(function ($category) use ($locale, $project) {
            // اگر locale مقدار نداشت، از زبان جاری اپلیکیشن پر شود
            if (empty($category->locale)) {
                $category->locale = $locale;
            }

            // اگر project_id مقدار نداشت، از هدر یا سشن گرفته شود
            if (empty($category->project_id)) {
                $category->project_id = data_get($project, 'id');
            }
        });
    }

}
