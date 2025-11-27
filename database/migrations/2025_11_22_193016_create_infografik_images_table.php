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
        Schema::create('infografik_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infografik_id')->constrained('infografik')->onDelete('cascade');
            $table->string('image');
            $table->integer('sort')->default(0);
            $table->string('caption_id')->nullable();
            $table->string('caption_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infografik_images');
    }
};
