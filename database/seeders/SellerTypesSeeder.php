<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellerTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('seller_types')->insert([
            [
                'name' => 'type_1',
                'en_description' => 'Provides discounts on all products without exception.',
                'ar_description' => 'يوفر خصومات على جميع المنتجات دون استثناء',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'type_2',
                'en_description' => 'Provides discounts on all products except for specific items.',
                'ar_description' => 'يوفر خصومات على جميع المنتجات باستثناء بعض المنتجات المحددة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'type_3',
                'en_description' => 'Does not have a fixed discount percentage but offers discounts on specific products or services.',
                'ar_description' => 'لا يحتوي على نسبة خصم ثابتة ولكن يوفر خصومات على منتجات أو خدمات معينة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'type_4',
                'en_description' => 'Does not have a fixed discount percentage but offers discounts on specific special deals.',
                'ar_description' => 'لا يحتوي على نسبة خصم ثابتة ولكن يوفر خصومات على عروض خاصة معينة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
