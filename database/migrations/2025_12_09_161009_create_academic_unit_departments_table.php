<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_unit_departments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_unit_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('title');
            $table->string('short_code')->nullable();
            $table->unsignedInteger('position')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_unit_departments');
    }
};
