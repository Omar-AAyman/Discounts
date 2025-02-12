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
        Schema::table('products', function (Blueprint $table) {
            $table->double('discount_percentage', 10, 2)->nullable()->after('price')->comment('Discount percentage for Type 1 sellers');
            $table->double('discount_amount', 10, 2)->nullable()->after('discount_percentage')->comment('Discount amount for Type 1 sellers');
            $table->boolean('is_excluded_from_discount')->default(false)->after('discount_amount')->comment('Indicates if the product is excluded from store-wide discounts (for Type 3 sellers)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['discount_percentage', 'discount_amount', 'is_excluded_from_discount']);
        });
    }
};
