<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_page_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_page_id')->constrained('academic_pages')->onDelete('cascade');

            $table->string('block_type'); // hero, rich_text, cards, quick_links, staff_list, notices, downloads, gallery, contact, etc.
            $table->json('data')->nullable(); // flexible per block type
            $table->unsignedInteger('position')->default(0);
            $table->enum('status', ['published','draft','archived'])->default('published');

            $table->timestamps();
            $table->index(['academic_page_id','position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_page_blocks');
    }
};
