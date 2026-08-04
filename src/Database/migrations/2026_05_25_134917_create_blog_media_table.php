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
        Schema::create('blog_media', function (Blueprint $table) {
            $table->id();
            $table->string('disk')->default('public'); // filesystem disk
            $table->string('path'); // file path relative to disk root
            $table->string('name')->nullable(); // original file name
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('size')->default(0);
            $table->json('meta')->nullable(); // alt text, crops, etc.
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->boolean('used')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_media');
    }
};
