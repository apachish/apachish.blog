<?php

namespace Apachish\Blog;

use Apachish\Blog\App\Models\Post;
use Apachish\Blog\App\Observers\BlogPostObserver;
use Apachish\Blog\Livewire\Categories\Index;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class BlogServiceProvider extends ServiceProvider
{
    public function register()
    {
        // ثبت تنظیمات یا اتصالات
        $this->mergeConfigFrom(__DIR__.'/config/seeder.php','package-seeders');

    }

    public function boot()
    {
        Post::observe(BlogPostObserver::class);

        Livewire::addNamespace(
            namespace: 'blog',
            classNamespace: 'Apachish\\Blog\\App\\Livewire',
            classPath: __DIR__ . '/Livewire',
            classViewPath: __DIR__ . '/../resources/views/livewire',
        );

        // بارگذاری مسیرها (Routes)
        Route::middleware('web')
            ->group(__DIR__.'/routes/web.php');
        // بارگذاری ویوها (Views)
        $this->loadViewsFrom(__DIR__.'/resources/views', 'blog');

        // بارگذاری مایگریشن‌ها (Migrations)
        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/lang', 'blog');

        $this->publishes([
            __DIR__.'/lang' => $this->app->langPath('vendor/blog'),
        ], 'blog-lang');

        $this->publishes([
            __DIR__.'/Database/Seeders/' => database_path('seeders'),
        ], 'blog-seeders');
    }
}

