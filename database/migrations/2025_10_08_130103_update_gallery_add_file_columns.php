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
        Schema::table('gallery', function (Blueprint $table) {
            //
            if (Schema::hasColumn('gallery', 'path')) {
                $table->dropColumn('path');
            }

            // tambahkan kolom file_en dan file_id
            if (!Schema::hasColumn('gallery', 'file_en')) {
                $table->text('file_en')->nullable()->after('status');
            }

            if (!Schema::hasColumn('gallery', 'file_id')) {
                $table->text('file_id')->nullable()->after('file_en');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery', function (Blueprint $table) {
            //
            if (Schema::hasColumn('gallery', 'file_en')) {
                $table->dropColumn('file_en');
            }

            if (Schema::hasColumn('gallery', 'file_id')) {
                $table->dropColumn('file_id');
            }

            // rollback restore kolom path
            if (!Schema::hasColumn('gallery', 'path')) {
                $table->string('path')->nullable();
            }
        });
    }
};
