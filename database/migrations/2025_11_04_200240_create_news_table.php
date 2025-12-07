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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('thumb_image')->nullable();
            $table->string('content_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->string('author')->nullable();
            $table->date('published_at')->nullable();
            $table->integer('read_time')->default(1);
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_featured')->default(false)->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->enum('status', ['draft', 'published', 'unpublished'])->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
