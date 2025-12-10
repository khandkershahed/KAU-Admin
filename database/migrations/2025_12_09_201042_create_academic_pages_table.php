<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_site_id')->nullable()->constrained('academic_sites')->onDelete('cascade');
            $table->foreignId('nav_item_id')->nullable()->constrained('academic_nav_items')->onDelete('cascade');
            $table->string('page_key')->nullable(); //must be same as the slug of its selected academic_nav_item's menu_key
            $table->string('slug');     //must be same as the slug of its selected academic_nav_item's slug

            $table->string('title');

            $table->boolean('is_home')->default(false);
            $table->boolean('is_department_boxes')->default(false);
            $table->boolean('is_faculty_members')->default(false);
            // Banner
            $table->string('banner_title')->nullable();
            $table->string('banner_subtitle')->nullable();
            $table->string('banner_button')->nullable();
            $table->string('banner_button_url')->nullable();

            $table->string('banner_image')->nullable();
            $table->longText('content')->nullable();
            // Meta
            $table->string('meta_title')->nullable();
            $table->string('meta_tags')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();

            $table->enum('status',['published','draft','archived'])->default('published');
 // published/draft/archived
            $table->unsignedInteger('position')->default(0);

            $table->timestamps();

            $table->unique(['academic_site_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_pages');
    }
};
