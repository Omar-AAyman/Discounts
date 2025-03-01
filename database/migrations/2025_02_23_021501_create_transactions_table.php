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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('subscription_id')->constrained();
            $table->string('lahza_transaction_id')->unique();
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3);
            $table->string('status');
            $table->string('reference')->unique();
            $table->text('metadata')->nullable();
            $table->string('channel')->nullable();
            $table->string('ip_address')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
