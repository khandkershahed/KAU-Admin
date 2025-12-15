<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_departments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_site_id')->nullable()->constrained('academic_sites')->onDelete('cascade');
            $table->string('title');
            $table->string('short_code')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->enum('status',['published','draft','archived'])->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_departments');
    }
};
