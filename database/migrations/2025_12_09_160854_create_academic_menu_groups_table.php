<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_menu_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedInteger('position')->default(0);
            $table->enum('status',['published','draft','archived'])->default('published');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_menu_groups');
    }
};
