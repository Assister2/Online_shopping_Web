<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('cities')->delete();

        \DB::table('cities')->insert(
        	array(
			  	array('id' => '1','country_id' => '231','name' => 'California','cost' => '10.00','created_at' => '2021-09-22 07:05:35','updated_at' => '2021-09-22 07:05:35'),
			  	array('id' => '2','country_id' => '99','name' => 'Madurai','cost' => '10.00','created_at' => '2021-09-28 01:56:49','updated_at' => '2021-09-28 01:56:49')
			),
        );
    }
}
