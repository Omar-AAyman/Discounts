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
                'en_description' => 'This type of seller provides discounts on all products without exception.',
                'ar_description' => 'النوع الأول من التجار خصم على كل المنتجات دون إستثناء',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'type_2',
                'en_description' => 'This type of seller provides discounts on all products except for specific items.',
                'ar_description' => 'النوع الثاني من التاجر الخصم على كل المنتجات بإستثناء منتجات معينة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'type_3',
                'en_description' => 'This type of seller does not have a fixed discount percentage but offers discounts on specific special deals.',
                'ar_description' => 'النوع الثالث من التاجر دون نسبة خصم ثابتة فقط خصم على عروض خاصة معينة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'type_4',
                'en_description' => 'This type of seller does not have a fixed discount percentage but offers discounts on specific products or services.',
                'ar_description' => 'النوع الرابع من التاجر دون نسبة خصم ثابتة فقط خصم على منتجات أو خدمات معينة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
