<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WishlistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('wishlists')->delete();

        \DB::table('wishlists')->insert(
        	array(
			  	array('id' => '1','user_id' => '9','product_id' => '8','created_at' => '2021-09-22 06:59:40','updated_at' => '2021-09-22 06:59:40'),
			  	array('id' => '7','user_id' => '45','product_id' => '32','created_at' => '2021-11-09 19:00:12','updated_at' => '2021-11-09 19:00:12'),
			  	array('id' => '9','user_id' => '33','product_id' => '31','created_at' => '2021-11-18 00:52:49','updated_at' => '2021-11-18 00:52:49'),
			  	array('id' => '10','user_id' => '33','product_id' => '27','created_at' => '2021-11-18 00:53:34','updated_at' => '2021-11-18 00:53:34'),
			  	array('id' => '11','user_id' => '53','product_id' => '15','created_at' => '2021-11-18 21:59:10','updated_at' => '2021-11-18 21:59:10')
			),
        );
    }
}
