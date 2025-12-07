<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /* ===========================================================
            1. HOMEPAGE SECTIONS (ORDER + VISIBILITY)
        =========================================================== */
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();    // banner, vc_message, explore, faculty, glance, about
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        /* ===========================================================
            2. BANNER SLIDER (MULTIPLE SLIDES)
        =========================================================== */
        Schema::create('homepage_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->string('image_path')->nullable(); // Metronic image-input
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        /* ===========================================================
            3. VC MESSAGE SECTION (SINGLE ROW)
        =========================================================== */
        Schema::create('homepage_vc_message', function (Blueprint $table) {
            $table->id();
            $table->string('vc_name')->nullable();
            $table->string('vc_designation')->nullable();
            $table->string('vc_image')->nullable();
            $table->string('message_title')->nullable();
            $table->mediumText('message_text')->nullable();
            $table->string('button_name')->nullable();
            $table->string('button_url')->nullable();
            $table->timestamps();
        });

        /* ===========================================================
            4. EXPLORE KAU (TITLE + MULTIPLE BOXES)
        =========================================================== */
        Schema::create('homepage_explore', function (Blueprint $table) {
            $table->id();
            $table->string('section_title')->nullable();
            $table->timestamps();
        });

        Schema::create('homepage_explore_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('explore_id');
            $table->string('icon')->nullable();
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();

            $table->foreign('explore_id')
                ->references('id')
                ->on('homepage_explore')
                ->onDelete('cascade');

            $table->index('explore_id');
        });

        /* ===========================================================
            5. FACULTIES SECTION (TITLE + SUBTITLE)
        =========================================================== */
        Schema::create('homepage_faculty', function (Blueprint $table) {
            $table->id();
            $table->string('section_title')->nullable();
            $table->string('section_subtitle')->nullable();
            $table->timestamps();
        });

        /* ===========================================================
            6. KAU AT A GLANCE (TITLE + BOXES)
        =========================================================== */
        Schema::create('homepage_glance', function (Blueprint $table) {
            $table->id();
            $table->string('section_title')->nullable();
            $table->string('section_subtitle')->nullable();
            $table->timestamps();
        });

        Schema::create('homepage_glance_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('glance_id');
            $table->string('icon')->nullable();
            $table->string('title')->nullable();
            $table->string('number')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();

            $table->foreign('glance_id')
                ->references('id')
                ->on('homepage_glance')
                ->onDelete('cascade');

            $table->index('glance_id');
        });

        /* ===========================================================
            7. ABOUT SECTION (TEXT + 5 IMAGES)
        =========================================================== */
        Schema::create('homepage_about', function (Blueprint $table) {
            $table->id();
            $table->string('badge')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->mediumText('description')->nullable();

            $table->string('experience_badge')->nullable();
            $table->string('experience_title')->nullable();

            $table->json('images')->nullable(); // up to 5 images stored as JSON array
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_about');
        Schema::dropIfExists('homepage_glance_items');
        Schema::dropIfExists('homepage_glance');
        Schema::dropIfExists('homepage_faculty');
        Schema::dropIfExists('homepage_explore_items');
        Schema::dropIfExists('homepage_explore');
        Schema::dropIfExists('homepage_vc_message');
        Schema::dropIfExists('homepage_banners');
        Schema::dropIfExists('homepage_sections');
    }
};
