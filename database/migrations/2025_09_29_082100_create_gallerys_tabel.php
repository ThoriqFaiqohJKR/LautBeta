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
        Schema::create('gallery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('database_id')
                ->constrained('database')
                ->cascadeOnDelete();
            $table->string('title_id')->required();
            $table->string('title_en')->required();
            $table->text('description_id')->required();
            $table->text('description_en')->required();
            $table->longText('content_id')->required();
            $table->longText('content_en')->required();
            $table->enum('status', ['on', 'off'])->default('on');
            $table->enum('type', ['photo', 'video'])->default('photo');
            $table->string('path');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery');
    }
};
