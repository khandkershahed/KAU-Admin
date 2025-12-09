<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_sites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_menu_group_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('name');        // full name
            $table->string('short_name');  // VABS, AG, ...
            $table->string('slug')->unique(); // vabs, ag, fos...

            // For routing / domains
            $table->string('base_url')->nullable();    // e.g. /vabs
            $table->string('subdomain')->nullable();   // e.g. vabs.kau.ac.bd
            $table->text('short_description')->nullable();

            // Branding
            $table->string('theme_primary_color')->nullable();
            $table->string('theme_secondary_color')->nullable();
            $table->string('logo_path')->nullable();

            $table->unsignedInteger('menu_order')->default(0);

            $table->enum('status', ['draft', 'published', 'archived'])->default('published');

            $table->json('config')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_sites');
    }
};
