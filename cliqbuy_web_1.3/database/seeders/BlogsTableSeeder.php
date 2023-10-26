<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class BlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('blogs')->delete();
        
        \DB::table('blogs')->insert(
        	array(
			  	array('id' => '1','category_id' => '1','title' => 'New Arrival','slug' => 'new-arrival','short_description' => 'Blogs','description' => NULL,'banner' => '51','meta_title' => NULL,'meta_img' => '48','meta_description' => NULL,'meta_keywords' => NULL,'status' => '1','created_at' => '2021-09-22 06:38:42','updated_at' => '2021-09-22 06:50:12','deleted_at' => NULL)
			),
        );
    }
}
