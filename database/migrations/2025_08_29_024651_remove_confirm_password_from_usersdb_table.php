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
        Schema::table('usersdb', function (Blueprint $table) {
            // Schema::table('usersdb', function (Blueprint $table) {
            $table->dropColumn('confirm_password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usersdb', function (Blueprint $table) {
            //
            $table->string('confirm_password')->nullable();
        });
    }
};
