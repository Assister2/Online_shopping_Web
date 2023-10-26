<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class StaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('staff')->delete();

        \DB::table('staff')->insert(
        	array(
		  		array('id' => '1','user_id' => '19','role_id' => '1','created_at' => '2021-09-28 18:23:25','updated_at' => '2021-09-28 18:23:25'),
		  		array('id' => '3','user_id' => '51','role_id' => '2','created_at' => '2021-11-17 02:05:35','updated_at' => '2021-11-17 02:05:35')
			),
       	); 	
    }
}
