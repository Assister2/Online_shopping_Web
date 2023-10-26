<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlashDealProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('flash_deal_products')->delete();

        \DB::table('flash_deal_products')->insert(
        	array(
			  array('id' => '25','flash_deal_id' => '1','product_id' => '2','discount' => '0.00','discount_type' => NULL,'created_at' => '2021-10-02 01:01:10','updated_at' => '2021-10-02 01:01:10'),
			  array('id' => '26','flash_deal_id' => '1','product_id' => '6','discount' => '0.00','discount_type' => NULL,'created_at' => '2021-10-02 01:01:10','updated_at' => '2021-10-02 01:01:10'),
			  array('id' => '27','flash_deal_id' => '1','product_id' => '7','discount' => '0.00','discount_type' => NULL,'created_at' => '2021-10-02 01:01:10','updated_at' => '2021-10-02 01:01:10'),
			  array('id' => '28','flash_deal_id' => '1','product_id' => '8','discount' => '0.00','discount_type' => NULL,'created_at' => '2021-10-02 01:01:10','updated_at' => '2021-10-02 01:01:10'),
			  array('id' => '29','flash_deal_id' => '1','product_id' => '9','discount' => '0.00','discount_type' => NULL,'created_at' => '2021-10-02 01:01:10','updated_at' => '2021-10-02 01:01:10'),
			  array('id' => '30','flash_deal_id' => '1','product_id' => '16','discount' => '0.00','discount_type' => NULL,'created_at' => '2021-10-02 01:01:10','updated_at' => '2021-10-02 01:01:10')
			),
        );
    }
}
