<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payments')->delete();

        \DB::table('payments')->insert(
        	array(
			  	array('id' => '1','seller_id' => '2','amount' => '10.00','payment_details' => NULL,'payment_method' => 'bank_payment','txn_code' => NULL,'created_at' => '2021-10-24 18:04:49','updated_at' => '2021-10-24 18:04:49')
			),
        );
    }
}
