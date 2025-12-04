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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            // Foreign key to notice_categories
            $table->foreignId('category_id')->nullable()->constrained('notice_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('body')->nullable();
            $table->date('publish_date')->nullable();
            // Attachments (JSON array to store multiple files)
            // Example: ["notice.pdf", "doc_file.docx"]
            $table->json('attachments')->nullable();
            // Small preview attachment type (PDF, DOC, JPG)
            $table->string('attachment_type')->nullable();
            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_tags')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('is_featured')->default(false)->nullable();
            $table->string('status')->default('published')->nullable(); // draft / published / archived
            // Who created the notice (Admin users)
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
