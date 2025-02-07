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
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign(['store_id']);


            $table->dropColumn([
            'price_before_discount','title',
            'store_id',
            'img',]);
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            // Add back the dropped columns
            $table->decimal('price_before_discount', 10, 2)->nullable();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('img')->nullable();
    
          
        });
    }
};
