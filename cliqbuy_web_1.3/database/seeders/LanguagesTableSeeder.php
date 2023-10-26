<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('languages')->delete();

        \DB::table('languages')->insert(
        	array(
			  	array('id' => '1','name' => 'English','code' => 'en','rtl' => '0','created_at' => '2019-01-20 17:43:20','updated_at' => '2021-09-14 06:24:05'),
			  	array('id' => '3','name' => 'Bangla','code' => 'bd','rtl' => '0','created_at' => '2019-02-17 12:05:37','updated_at' => '2019-02-18 12:19:51'),
			  	array('id' => '4','name' => 'Arabic','code' => 'sa','rtl' => '1','created_at' => '2019-04-29 00:04:12','updated_at' => '2019-04-29 00:04:12')
			),
        );
    }
}
