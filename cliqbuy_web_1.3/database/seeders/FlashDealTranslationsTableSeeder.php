<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlashDealTranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('flash_deal_translations')->delete();

        \DB::table('flash_deal_translations')->insert(
        	array(
			  	array('id' => '1','flash_deal_id' => '1','title' => 'Mega Offer','lang' => 'en','created_at' => '2021-09-14 09:54:06','updated_at' => '2021-09-14 09:54:06')
			),
        );
    }
}
