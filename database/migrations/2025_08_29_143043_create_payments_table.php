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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('idKw');
            $table->string('user_name');
            $table->string('email');
            $table->string('pack');
            $table->decimal('amount', 10, 2);
            $table->string('payment_system');
            $table->string('status');
            $table->string('invoice_code');
            $table->date('date');
            $table->time('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
