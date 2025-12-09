<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_nav_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_site_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('academic_nav_items')
                  ->onDelete('cascade');

            $table->string('label');
            $table->string('menu_key')->nullable(); // 'home','about','departments',etc.
            $table->enum('type', ['route', 'page', 'external', 'group'])->default('page');
            // when type = 'page'
            $table->unsignedBigInteger('page_id')->nullable();
            // when type = 'route'
            $table->string('route_path')->nullable(); // e.g. '/departments', '/faculty-member'
            // when type = 'external'
            $table->string('external_url')->nullable();
            $table->string('icon')->nullable();

            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_nav_items');
    }
};
