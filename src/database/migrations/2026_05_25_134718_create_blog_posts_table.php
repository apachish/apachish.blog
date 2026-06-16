<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('status')->default('draft'); // draft, pending, published, private, trash
            $table->timestamp('published_at')->nullable(); // scheduled publishing
            $table->string('password')->nullable(); // password-protected posts
            $table->unsignedBigInteger('parent_id')->nullable(); // hierarchical posts (like pages)
            $table->string('template')->nullable(); // custom template
            $table->unsignedInteger('comment_count')->default(0);
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('status');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
