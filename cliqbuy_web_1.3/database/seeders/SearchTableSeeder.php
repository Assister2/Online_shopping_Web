<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class SearchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('searches')->delete();

        \DB::table('searches')->insert(
        	array(
			  array('id' => '2','query' => 'dcs','count' => '1','created_at' => '2020-03-08 05:59:09','updated_at' => '2020-03-08 05:59:09'),
			  array('id' => '3','query' => 'das','count' => '3','created_at' => '2020-03-08 05:59:15','updated_at' => '2020-03-08 05:59:50'),
			  array('id' => '4','query' => 'dd','count' => '2','created_at' => '2021-09-22 04:35:56','updated_at' => '2021-09-22 04:35:58'),
			  array('id' => '7','query' => 'Iphone','count' => '3','created_at' => '2021-10-10 22:28:47','updated_at' => '2021-11-10 00:48:13'),
			  array('id' => '8','query' => 'toys','count' => '1','created_at' => '2021-10-22 16:38:13','updated_at' => '2021-10-22 16:38:13'),
			  array('id' => '9','query' => 'Blazer','count' => '1','created_at' => '2021-10-24 21:55:09','updated_at' => '2021-10-24 21:55:09'),
			  array('id' => '10','query' => 'pencil','count' => '1','created_at' => '2021-11-05 23:06:50','updated_at' => '2021-11-05 23:06:50'),
			  array('id' => '11','query' => 'Stamper','count' => '2','created_at' => '2021-11-17 04:38:59','updated_at' => '2021-11-17 04:39:01'),
			  array('id' => '12','query' => 'Stamp','count' => '2','created_at' => '2021-11-17 04:39:11','updated_at' => '2021-11-17 04:39:13')
			)
        );
    }
}
