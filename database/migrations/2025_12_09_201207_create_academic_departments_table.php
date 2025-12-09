<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_departments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_site_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('title');
            $table->string('short_code');  // VAH, VPH...
            $table->string('slug');        // anatomy-and-histology

            $table->text('description')->nullable();

            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['academic_site_id', 'short_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_departments');
    }
};
