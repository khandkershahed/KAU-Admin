<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_pages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_site_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->unsignedBigInteger('nav_item_id')->nullable(); // optional

            $table->string('page_key'); // about, facilities, academic_program, ...
            $table->string('slug');     // about-vabs, academic-program
            $table->string('title');
            $table->string('subtitle')->nullable();

            $table->enum('page_type', ['home', 'custom', 'academic_subpage', 'info'])
                  ->default('custom');

            // Banner
            $table->string('banner_title')->nullable();
            $table->string('banner_subtitle')->nullable();
            $table->string('banner_image_path')->nullable();

            $table->json('layout_config')->nullable();

            // Meta
            $table->string('meta_title')->nullable();
            $table->string('meta_tags')->nullable();
            $table->text('meta_description')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('position')->default(0);

            $table->timestamps();

            $table->unique(['academic_site_id', 'page_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_pages');
    }
};
