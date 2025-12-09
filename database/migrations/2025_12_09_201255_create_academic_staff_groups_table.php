<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_staff_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_site_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('academic_department_id')
                  ->nullable()
                  ->constrained('academic_departments')
                  ->nullOnDelete();

            $table->string('title'); // Vice-Chancellor, Officers, Teachers...

            $table->unsignedInteger('position')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_staff_groups');
    }
};
