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
            $table->date('tanggal_publikasi')
                ->required()
                ->after('content_en'); 

            $table->enum('publikasi', ['draf', 'publish'])
                ->default('draf')
                ->after('tanggal_publikasi'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery', function (Blueprint $table) {
             $table->dropColumn(['tanggal_publikasi', 'publikasi']);
        });
    }
};
