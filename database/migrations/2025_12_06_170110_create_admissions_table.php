<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            // For nested menu (Undergraduate -> Admission Info, etc.)
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('admissions')
                ->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            // menu | page | external
            $table->string('type')->default('menu');
            // for "Application Link" etc.
            $table->text('external_url')->nullable();
            // page content
            $table->string('banner_image')->nullable();
            $table->longText('content')->nullable();
            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_tags')->nullable();
            $table->text('meta_description')->nullable();

            $table->integer('position')->default(0);
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
