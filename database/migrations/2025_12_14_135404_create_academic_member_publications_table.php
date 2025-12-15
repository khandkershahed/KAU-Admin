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
        Schema::create('academic_member_publications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('academic_staff_member_id')->constrained('academic_staff_members')->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['journal', 'conference'])->nullable();
            $table->string('journal_or_conference_name')->nullable();
            $table->string('publisher')->nullable();
            $table->year('year')->nullable();
            $table->string('doi')->nullable();
            $table->string('url')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_member_publications');
    }
};
