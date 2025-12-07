<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();

            // Basic
            $table->string('title');
            $table->string('slug')->unique();
            // For dropdown label if you ever want different
            $table->string('menu_label')->nullable();

            // Banner / hero (for "About KAU" hero section)
            $table->string('banner_title')->nullable();
            $table->string('banner_subtitle')->nullable();
            $table->string('banner_icon')->nullable(); // e.g. 'fa-solid fa-graduation-cap'
            $table->string('banner_image')->nullable(); // path if you want

            // Short text
            $table->text('excerpt')->nullable();

            // Full body (HTML)
            $table->longText('content')->nullable();

            // Sorting + status
            $table->unsignedInteger('menu_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])
                  ->default('published');

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_tags')->nullable();
            $table->text('meta_description')->nullable();

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
