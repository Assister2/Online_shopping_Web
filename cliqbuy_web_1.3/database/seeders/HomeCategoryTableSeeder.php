<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class HomeCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('home_categories')->delete();

        \DB::table('home_categories')->insert(
        	array(
			  	array('id' => '1','category_id' => '1','subsubcategories' => '["1"]','status' => '1','created_at' => '2019-03-12 12:08:23','updated_at' => '2019-03-12 12:08:23'),
			  	array('id' => '2','category_id' => '2','subsubcategories' => '["10"]','status' => '1','created_at' => '2019-03-12 12:14:54','updated_at' => '2019-03-12 12:14:54')
			),
        );
    }
}
