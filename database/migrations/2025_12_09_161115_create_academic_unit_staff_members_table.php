<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_unit_staff_members', function (Blueprint $table) {
            $table->id();

            $table->foreignId('staff_section_id')
                  ->constrained('academic_unit_staff_sections')
                  ->onDelete('cascade');

            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedInteger('position')->default(0);

            // all social / external links stored as JSON array
            // [
            //   {"icon": "fa-solid fa-google-scholar", "url": "https://..."},
            //   ...
            // ]
            $table->json('links')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_unit_staff_members');
    }
};
