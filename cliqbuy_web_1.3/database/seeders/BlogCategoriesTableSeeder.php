<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class BlogCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('blog_categories')->delete();

        \DB::table('blog_categories')->insert(
        	array(
			  	array('id' => '1','category_name' => 'BOOKS','slug' => 'BOOKS','created_at' => '2021-09-22 06:37:34','updated_at' => '2021-09-22 06:49:31','deleted_at' => NULL)
			),
        );
    }
}
