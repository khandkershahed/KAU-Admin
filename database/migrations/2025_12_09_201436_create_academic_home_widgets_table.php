<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_home_widgets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_site_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->enum('widget_type', ['hero', 'slider', 'stat', 'link_box', 'custom_html'])
                  ->default('hero');

            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('content')->nullable();

            $table->string('image_path')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();

            $table->string('icon')->nullable(); // use icon-picker here
            $table->json('extra')->nullable();  // stats or extra config

            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_home_widgets');
    }
};
