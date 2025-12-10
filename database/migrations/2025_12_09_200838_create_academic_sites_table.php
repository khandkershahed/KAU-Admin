<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_menu_group_id')->nullable()->constrained('academic_menu_groups')->onDelete('cascade');
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('slug')->unique(); // e.g. 'vabs', 'ag', etc.
            $table->text('short_description')->nullable();
            $table->string('theme_primary_color')->nullable();
            $table->string('theme_secondary_color')->nullable();
            $table->string('logo_path')->nullable();

            $table->unsignedInteger('position')->default(0);
            $table->enum('status',['published','draft','archived'])->default('published');

            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_sites');
    }
};
