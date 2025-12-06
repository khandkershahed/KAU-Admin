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
        Schema::create('admin_office_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('admin_offices')->onDelete('cascade');

            $table->string('title')->nullable();
            $table->string('section_type')->nullable();

            $table->longText('content')->nullable();
            $table->json('extra')->nullable();
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
        Schema::dropIfExists('admin_office_sections');
    }
};
