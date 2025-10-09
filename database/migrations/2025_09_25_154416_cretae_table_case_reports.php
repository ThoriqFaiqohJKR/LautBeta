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
        Schema::create('case_report', function (Blueprint $table) {
            $table->id();
            $table->string('title_id')->required();
            $table->string('title_en')->required();
            $table->text('description_id')->required();
            $table->text('description_en')->required();
            $table->longText('content_id')->required();
            $table->longText('content_en')->required();
            $table->string('image')->required();
            $table->date('tanggal_publikasi')->required();
            $table->enum('publikasi', ['draf', 'publish'])->default('draf');
            $table->enum('status', ['on', 'off'])->default('on');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_report');
    }
};
