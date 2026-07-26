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
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('locale', 5); // 'fa', 'en'
            $table->string('featured_image')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->unsignedInteger('comment_count')->default(0);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('unique_views_count')->default(0);
            $table->unsignedInteger('estimated_reading_time')->nullable(); // minutes
            $table->unsignedInteger('average_reading_time')->nullable(); // seconds

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('project_users')->onDelete('cascade');
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
