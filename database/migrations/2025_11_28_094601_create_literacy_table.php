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
        Schema::create('literacy', function (Blueprint $table) {
            $table->id();

            $table->string('image');

            $table->string('title_id');
            $table->string('title_en');

            $table->text('description_id');
            $table->text('description_en');

            $table->longText('content_id');
            $table->longText('content_en');

            $table->date('tanggal_publikasi');

            $table->enum('publikasi', ['draft', 'publish'])->default('draft');
            $table->enum('status', ['on', 'off'])->default('off');
            $table->enum('type', ['journal', 'grafik'])->default('grafik');

            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('literacy');
    }
};
