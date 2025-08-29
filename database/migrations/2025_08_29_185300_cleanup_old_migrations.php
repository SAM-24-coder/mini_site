<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('usersdb')) {
            Schema::dropIfExists('usersdb');
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('usersdb')) {
            Schema::create('usersdb', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamps();
            });
        }
    }
};
