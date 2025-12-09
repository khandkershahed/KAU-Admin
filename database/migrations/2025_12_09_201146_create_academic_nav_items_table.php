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

            $table->unsignedBigInteger('parent_id')->nullable(); // self-reference

            $table->string('label');         // "Home", "About VABS"
            $table->string('menu_key')->nullable(); // home, about, departments, etc.

            $table->enum('type', ['route', 'page', 'external'])
                  ->default('page');

            $table->foreignId('page_id')->nullable()
                  ->constrained('academic_pages')
                  ->nullOnDelete();

            $table->string('route_name')->nullable();
            $table->string('external_url')->nullable();

            $table->string('icon')->nullable(); // use icon-picker here

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
