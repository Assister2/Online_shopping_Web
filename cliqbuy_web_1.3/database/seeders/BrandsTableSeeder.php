<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('brands')->delete();

        \DB::table('brands')->insert(
        	array(
			  	array('id' => '1','name' => 'Apple','logo' => '61','top' => '1','slug' => 'apple-12','meta_title' => 'Apple','meta_description' => NULL,'created_at' => '2019-03-12 11:35:56','updated_at' => '2021-10-01 20:55:23'),
			  	array('id' => '2','name' => 'HP','logo' => '62','top' => '1','slug' => 'hp brand','meta_title' => 'Hp','meta_description' => NULL,'created_at' => '2019-03-12 11:36:13','updated_at' => '2021-10-01 20:57:23'),
			  	array('id' => '3','name' => 'Samsung','logo' => '64','top' => '0','slug' => 'samsung-hjqof','meta_title' => 'Samsung','meta_description' => NULL,'created_at' => '2021-09-14 06:49:53','updated_at' => '2021-10-01 20:58:54'),
			  	array('id' => '4','name' => 'Levis','logo' => '63','top' => '0','slug' => 'levis-ifenm','meta_title' => 'Levis','meta_description' => NULL,'created_at' => '2021-09-14 07:24:49','updated_at' => '2021-10-01 20:58:12'),
			  	array('id' => '5','name' => 'Preethi','logo' => '65','top' => '0','slug' => 'Preethi-hs3yW','meta_title' => NULL,'meta_description' => NULL,'created_at' => '2021-10-01 20:59:44','updated_at' => '2021-10-01 20:59:44'),
			  	array('id' => '6','name' => 'Damro','logo' => '66','top' => '0','slug' => 'Damro-2rD3L','meta_title' => NULL,'meta_description' => NULL,'created_at' => '2021-10-01 21:00:20','updated_at' => '2021-10-01 21:00:20'),
			  	array('id' => '7','name' => 'JBL','logo' => '67','top' => '0','slug' => 'JBL-Luwee','meta_title' => NULL,'meta_description' => NULL,'created_at' => '2021-10-01 21:02:06','updated_at' => '2021-10-01 21:02:06'),
			  	array('id' => '8','name' => 'Nissan','logo' => '68','top' => '0','slug' => 'Nissan-peFgJ','meta_title' => NULL,'meta_description' => NULL,'created_at' => '2021-10-01 21:02:38','updated_at' => '2021-10-01 21:02:38'),
			  	array('id' => '9','name' => 'oppo','logo' => '120','top' => '0','slug' => 'oppo-eZKte','meta_title' => 'oppo','meta_description' => 'Technology as an art form','created_at' => '2021-11-09 18:19:25','updated_at' => '2021-11-09 18:19:25')
			),
        );
    }
}
