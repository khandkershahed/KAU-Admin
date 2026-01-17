<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_index', function (Blueprint $table) {
            $table->id();

            // Entity identity
            $table->string('entity_type'); // page|notice|news|event|tender|person etc
            $table->unsignedBigInteger('entity_id');

            // Owner context (main|site|department|office)
            $table->string('owner_type')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();

            // Search fields
            $table->string('title');
            $table->longText('body')->nullable();
            $table->string('url')->nullable();

            // Publish metadata
            $table->timestamp('published_at')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
            $table->index(['owner_type', 'owner_id']);
            $table->index(['published_at']);

            // Fulltext (MySQL/InnoDB supported versions)
            try {
                $table->fullText(['title', 'body']);
            } catch (\Throwable $e) {
                // ignore for DB engines that do not support it
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_index');
    }
};
