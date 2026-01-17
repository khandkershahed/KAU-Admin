<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained()->cascadeOnDelete();
            $table->enum('item_type',['image','video']);
            $table->string('media_path')->nullable();
            $table->string('video_url')->nullable();
            $table->string('title')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('gallery_items');
    }
};
