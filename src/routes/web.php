<?php
use Apachish\Blog\App\Livewire\Posts\Index as PostIndex;
use Apachish\Blog\App\Livewire\Posts\CreateOrUpdate as PostCreateOrUpdate;
use Apachish\Blog\App\Livewire\Categories\Index as CategoriesIndex;
use Apachish\Blog\App\Livewire\Categories\CreateOrUpdate as CategoriesCreateOrUpdate;

use Apachish\Blog\App\Livewire\Tags\Index as TagsIndex;
use Apachish\Blog\App\Livewire\Tags\CreateOrUpdate as CreateTagsCreateOrUpdate;

use Apachish\Blog\App\Livewire\Comments\Index as CommentsIndex;
use Apachish\Blog\App\Livewire\Comments\CreateOrUpdate as CreateCommentsCreateOrUpdate;


Route::prefix('panel/{api_key}/blog')->middleware([ 'auth:web,project', 'verified',\App\Http\Middleware\SetProject::class])->group(function () {
    Route::post('/editor/upload', function (\Illuminate\Http\Request $request) {

        $path = $request->file('image')->store('editor', 'public');

        return response()->json([
            'url' => Storage::url($path)
        ]);

    });
    Route::prefix('posts')->group(function () {
        Route::livewire('/', PostIndex::class)->name('blog.posts.index');
        Route::livewire('/create', PostCreateOrUpdate::class)->name('blog.posts.create');
        Route::livewire('/edit/{post_id}', PostCreateOrUpdate::class)->name('blog.posts.edit');
    });
    Route::prefix('categories')->group(function () {
        Route::livewire('/', CategoriesIndex::class)->name('blog.categories.index');
        Route::livewire('/create', CategoriesCreateOrUpdate::class)->name('blog.categories.create');
        Route::livewire('/edit/{category_id}', CategoriesCreateOrUpdate::class)->name('blog.categories.edit');
    });
    Route::prefix('tags')->group(function () {
        Route::livewire('/', TagsIndex::class)->name('blog.tags.index');
        Route::livewire('/create', CreateTagsCreateOrUpdate::class)->name('blog.tags.create');
        Route::livewire('/edit/{category_id}', CreateTagsCreateOrUpdate::class)->name('blog.tags.edit');

    });
    Route::prefix('comments')->group(function () {
        Route::livewire('/', CommentsIndex::class)->name('blog.comments.index');
        Route::livewire('/create', CreateCommentsCreateOrUpdate::class)->name('blog.comments.create');
    });
});
