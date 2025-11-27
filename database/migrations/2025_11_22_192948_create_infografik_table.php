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
        Schema::create('infografik', function (Blueprint $table) {
            $table->id();
            $table->string('title_id')->nullable();
            $table->string('title_en')->nullable();
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->string('slug')->unique();
            $table->string('main_image')->nullable();
            $table->enum('publikasi', ['publish', 'draft'])->default('draft');
            $table->date('tanggal_publikasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infografik');
    }
};
