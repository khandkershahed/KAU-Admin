<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_nav_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_site_id')->nullable()->constrained('academic_sites')->onDelete('cascade');
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('academic_nav_items')
                ->onDelete('cascade');

            $table->string('label');
            $table->string('slug');
            $table->string('menu_key')->nullable(); // 'home','about','departments',etc.
            $table->enum('type', ['route', 'page', 'external', 'group'])->default('page');
            $table->string('external_url')->nullable(); // when type = 'external'
            $table->string('icon')->nullable();

            $table->unsignedInteger('position')->default(0);
            $table->enum('status',['published','draft','archived'])->default('published');

            $table->unique(['academic_site_id', 'slug']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_nav_items');
    }
};
