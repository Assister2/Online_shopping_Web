<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class SliderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('sliders')->delete();

        \DB::table('sliders')->insert(
        	array(
			  	array('id' => '7','photo' => 'uploads/sliders/slider-image.jpg','published' => '1','link' => NULL,'created_at' => '2019-03-12 11:28:05','updated_at' => '2019-03-12 11:28:05'),
			  	array('id' => '8','photo' => 'uploads/sliders/slider-image.jpg','published' => '1','link' => NULL,'created_at' => '2019-03-12 11:28:12','updated_at' => '2019-03-12 11:28:12')
			),
        );
    }
}
