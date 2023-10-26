<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandTranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('brand_translations')->delete();

        \DB::table('brand_translations')->insert(
        	array(
			  	
			  	array('id' => '1','brand_id' => '1','name' => 'Apple','lang' => 'en','created_at' => '2021-09-14 06:47:39','updated_at' => '2021-09-14 06:47:39'),
			  	
			  	array('id' => '2','brand_id' => '2','name' => 'HP','lang' => 'en','created_at' => '2021-09-14 06:49:24','updated_at' => '2021-09-14 06:49:24'),
			  	
			  	array('id' => '3','brand_id' => '3','name' => 'Samsung','lang' => 'en','created_at' => '2021-09-14 06:49:53','updated_at' => '2021-09-14 06:49:53'),
			  	
			  	array('id' => '4','brand_id' => '4','name' => 'Levis','lang' => 'en','created_at' => '2021-09-14 07:24:49','updated_at' => '2021-09-14 07:24:49')
			),
        );
    }
}
