<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_page_sections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_page_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('section_key')->nullable(); // intro, history, ...

            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();

            $table->longText('content')->nullable();
            $table->json('extra')->nullable(); // cards, stats, etc.

            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_page_sections');
    }
};
