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
            if (!Schema::hasColumn('journal', 'status')) {
                $table->enum('status', ['on', 'off'])->default('on')->after('publikasi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal', function (Blueprint $table) {
            if (Schema::hasColumn('journal', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
