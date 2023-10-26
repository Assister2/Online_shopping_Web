<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeoSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('seo_settings')->delete();

        \DB::table('seo_settings')->insert(
        	array(
			  	array('id' => '1','keyword' => 'bootstrap,responsive,template,developer','author' => 'cliqbuy','revisit' => '11','sitemap_link' => 'https://www.cliqbuy.com','description' => 'Cliqbuy Multi vendor system is such a platform to build a border less marketplace both for physical and digital goods.','created_at' => '2019-08-08 14:26:11','updated_at' => '2019-08-08 08:26:11')
			),
        );
    }
}
