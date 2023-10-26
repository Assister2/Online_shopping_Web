<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolicyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('policies')->delete();

        \DB::table('policies')->insert(
        	array(
			  	array('id' => '1','name' => 'support_policy','content' => NULL,'created_at' => '2019-10-29 18:24:45','updated_at' => '2019-01-22 10:43:15'),
			  	array('id' => '2','name' => 'return_policy','content' => NULL,'created_at' => '2019-10-29 18:24:47','updated_at' => '2019-01-24 11:10:11'),
			  	array('id' => '4','name' => 'seller_policy','content' => NULL,'created_at' => '2019-10-29 18:24:49','updated_at' => '2019-02-04 23:20:15'),
			  	array('id' => '5','name' => 'terms','content' => NULL,'created_at' => '2019-10-29 18:24:51','updated_at' => '2019-10-28 23:30:00'),
			  	array('id' => '6','name' => 'privacy_policy','content' => NULL,'created_at' => '2019-10-29 18:24:54','updated_at' => '2019-10-28 23:30:00')
			),
        );
    }
}
