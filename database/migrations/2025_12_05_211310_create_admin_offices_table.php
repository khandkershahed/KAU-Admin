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
        Schema::create('admin_offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('admin_groups')->onDelete('cascade');

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('banner_image')->nullable();
            $table->longText('description')->nullable();

            // SEO fields
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
        Schema::dropIfExists('admin_offices');
    }
};
