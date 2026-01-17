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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();

            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->string('venue')->nullable();
            $table->string('organizer')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('registration_url', 1000)->nullable();

            $table->string('banner_image')->nullable();
            $table->json('attachments')->nullable();
            $table->string('attachment_type')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_tags')->nullable();
            $table->text('meta_description')->nullable();

            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('is_featured')->default(false)->nullable();
            $table->string('status')->default('published')->nullable(); // draft / published / archived

            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
