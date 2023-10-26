<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellerRequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('seller_withdraw_requests')->delete();

        \DB::table('seller_withdraw_requests')->insert(
        	array(
			  	array('id' => '1','user_id' => '2','amount' => '10.00','message' => 'Test','status' => '1','viewed' => '1', 'currency_id' => 1, 'created_at' => '2021-09-27 17:24:22','updated_at' => '2021-10-24 18:04:49')
			),
        );	
    }
}
