<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_menu_group_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('icon')->nullable();           // fontawesome icon
            $table->string('name');                       // full title
            $table->string('slug')->unique();             // vabs, ag, fos, ...
            $table->string('short_name')->nullable();     // VABS, AG...
            $table->string('short_description')->nullable();
            $table->string('button_name')->nullable();    // "Go To VABS"

            $table->unsignedInteger('menu_order')->default(0);

            // base url and home flags
            $table->string('base_url')->nullable();       // /vabs, /ag, ...
            $table->string('home_layout')->default('faculty_home');
            $table->boolean('home_has_hero')->default(true);
            $table->boolean('home_has_department_grid')->default(true);

            // flexible JSON config for about/facilities/academic/research/programs/contact
            $table->json('config')->nullable();

            $table->enum('status', ['draft', 'published', 'archived'])
                  ->default('published');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_units');
    }
};
