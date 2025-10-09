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
        Schema::table('journal', function (Blueprint $table) {
            if (Schema::hasColumn('journal', 'file')) {
                $table->dropColumn('file');
            }

            // Tambah dua kolom baru
            $table->string('file_id')->nullable()->after('image');
            $table->string('file_en')->nullable()->after('file_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal', function (Blueprint $table) {
            $table->dropColumn(['file_id', 'file_en']);
            $table->string('file')->nullable();
        });
    }
};
