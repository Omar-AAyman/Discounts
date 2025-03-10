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
        Schema::create('on_boardings', function (Blueprint $table) {
            $table->id();
            $table->integer('slide_id')->nullable();
            $table->string('image_url')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('textbutton')->nullable();
            $table->integer('order')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('on_boardings');
    }
};
