<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->string('type')->default('image');
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->unique(['owner_type','owner_id','slug']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('galleries');
    }
};
