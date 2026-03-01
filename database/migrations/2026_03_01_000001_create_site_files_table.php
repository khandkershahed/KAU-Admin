<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_files', function (Blueprint $table) {
            $table->id();
            $table->string('token', 80)->unique();
            $table->string('disk', 50)->default('local'); // private storage disk
            $table->string('path');
            $table->string('original_name');
            $table->string('mime', 191)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_files');
    }
};
