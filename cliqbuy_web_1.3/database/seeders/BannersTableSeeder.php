<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class BannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('banners')->delete();

        \DB::table('banners')->insert(
        	array(
			  	array('id' => '4','photo' => 'uploads/banners/banner.jpg','url' => '#','position' => '1','published' => '1','created_at' => '2019-03-12 11:28:23','updated_at' => '2019-06-11 10:26:50'),
                array('id' => '5','photo' => 'uploads/banners/banner.jpg','url' => '#','position' => '1','published' => '1','created_at' => '2019-03-12 11:28:41','updated_at' => '2019-03-12 11:28:57'),
                array('id' => '6','photo' => 'uploads/banners/banner.jpg','url' => '#','position' => '2','published' => '1','created_at' => '2019-03-12 11:28:52','updated_at' => '2019-03-12 11:28:57'),
                array('id' => '7','photo' => 'uploads/banners/banner.jpg','url' => '#','position' => '2','published' => '1','created_at' => '2019-05-26 10:46:38','updated_at' => '2019-05-26 10:47:34'),
                array('id' => '8','photo' => 'uploads/banners/banner.jpg','url' => '#','position' => '2','published' => '1','created_at' => '2019-06-11 10:30:06','updated_at' => '2019-06-11 10:30:27'),
                array('id' => '9','photo' => 'uploads/banners/banner.jpg','url' => '#','position' => '1','published' => '1','created_at' => '2019-06-11 10:30:15','updated_at' => '2019-06-11 10:30:29'),
                array('id' => '10','photo' => 'uploads/banners/banner.jpg','url' => '#','position' => '1','published' => '0','created_at' => '2019-06-11 10:30:24','updated_at' => '2019-06-11 10:31:56')
			),
        );
    }
}
