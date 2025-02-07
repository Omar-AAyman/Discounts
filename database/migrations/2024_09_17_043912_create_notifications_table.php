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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('status')->nullable();
            
            $table->string('sector_name')->nullable();
            $table->string('licensed_operator_number')->nullable();
            $table->string('sector_representative')->nullable();
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();

            $table->string('email')->unique();
            $table->string('work_days')->nullable();
            $table->string('work_hours')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('sector_qr')->nullable();
            $table->string('contract_img')->nullable();
            $table->string('sector_img')->nullable();










            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
