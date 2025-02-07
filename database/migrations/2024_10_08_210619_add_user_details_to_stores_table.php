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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('seller_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number1')->nullable();
            $table->string('phone_number2')->nullable();

            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['seller_name','email','phone_number1','phone_number2','facebook','instagram']);
        });
    }
};
