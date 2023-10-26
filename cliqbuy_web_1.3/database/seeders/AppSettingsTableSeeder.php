<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AppSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('app_settings')->delete();

        \DB::table('app_settings')->insert(
        	array(
			  	array('id' => '1','name' => 'cliqbuy','logo' => 'uploads/logo/matggar.png','currency_id' => '1','currency_format' => 'symbol','facebook' => 'https://facebook.com','twitter' => 'https://twitter.com','instagram' => 'https://instagram.com','youtube' => 'https://youtube.com','google_plus' => 'https://google.com','created_at' => '2019-08-04 22:09:15','updated_at' => '2019-08-04 22:09:18')
			),
        );
    }
}
