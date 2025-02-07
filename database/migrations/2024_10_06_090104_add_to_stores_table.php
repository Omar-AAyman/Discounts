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
            $table->unsignedBigInteger('delegate_id')->nullable();
            $table->foreign('delegate_id')->references('id')->on('users')->onDelete('set null');
            $table->string('licensed_operator_number')->nullable();
            $table->string('sector_representative')->nullable();
            $table->string('location')->nullable();
            $table->string('work_days')->nullable();
            $table->string('work_hours')->nullable();
            $table->string('sector_qr')->nullable();
            $table->string('contract_img')->nullable();
            $table->string('store_img')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['delegate_id']);
            $table->dropColumn('delegate_id');
            $table->dropColumn('licensed_operator_number');
            $table->dropColumn('sector_representative');
            $table->dropColumn('location');
            $table->dropColumn('work_days');
            $table->dropColumn('work_hours');
            $table->dropColumn('sector_qr');
            $table->dropColumn('contract_img');
            $table->dropColumn('store_img');
    
        });
    }
};
