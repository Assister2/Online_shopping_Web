<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommissionHistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('commission_histories')->delete();

        \DB::table('commission_histories')->insert(
        	array(
			  	array('id' => '1','order_id' => '2','order_detail_id' => '2','seller_id' => '9','admin_commission' => '2.80','seller_earning' => '11.20','created_at' => '2021-09-27 17:22:39','updated_at' => '2021-09-27 17:22:39'),
			  	array('id' => '2','order_id' => '6','order_detail_id' => '8','seller_id' => '29','admin_commission' => '2330.00','seller_earning' => '9320.00','created_at' => '2021-10-09 06:57:40','updated_at' => '2021-10-09 06:57:40')
			),
        );
    }
}
