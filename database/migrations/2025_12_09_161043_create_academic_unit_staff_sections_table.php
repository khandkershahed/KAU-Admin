<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_unit_staff_sections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_unit_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('academic_unit_departments')
                  ->onDelete('cascade');

            $table->string('title');                 // e.g. Vice-Chancellor, Officers
            $table->unsignedInteger('position')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_unit_staff_sections');
    }
};
