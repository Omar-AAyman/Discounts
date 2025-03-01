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
        Schema::table('countries', function (Blueprint $table) {
            // Add name_ar column after name column
            $table->string('name_ar')->after('name')->nullable();

            // Add index for better search performance
            $table->index('name_ar');
        });

        Schema::table('cities', function (Blueprint $table) {
            // Add name_ar column after name column
            $table->string('name_ar')->after('name')->nullable();

            // Add index for better search performance
            $table->index('name_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            // Remove index first
            $table->dropIndex(['name_ar']);

            // Then remove the column
            $table->dropColumn('name_ar');
        });

        Schema::table('cities', function (Blueprint $table) {
            // Remove index first
            $table->dropIndex(['name_ar']);

            // Then remove the column
            $table->dropColumn('name_ar');
        });
    }
};
