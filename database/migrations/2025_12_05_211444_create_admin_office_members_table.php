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
        Schema::create('admin_office_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('admin_offices')->onDelete('cascade');

            $table->foreignId('section_id')->nullable()->constrained('admin_office_sections')->nullOnDelete();

            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();

            $table->string('type')->nullable(); // head / member / committee-member
            $table->integer('position')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_office_members');
    }
};
