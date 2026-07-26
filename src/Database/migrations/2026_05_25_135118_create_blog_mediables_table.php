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
        Schema::create('blog_mediables', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id');
            $table->morphs('mediable'); // This creates `mediable_type` and `mediable_id`
            $table->string('type')->default('default'); // Changed from nullable to default value
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            // Use a composite unique key instead of primary key with nullable column
            $table->unique(['media_id', 'mediable_type', 'mediable_id', 'type'], 'mediable_unique');

            $table->foreign('media_id')->references('id')->on('blog_media')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_mediables');
    }
};
