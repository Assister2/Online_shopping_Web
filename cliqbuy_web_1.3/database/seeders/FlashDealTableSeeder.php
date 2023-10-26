<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlashDealTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('flash_deals')->delete();

        \DB::table('flash_deals')->insert(
        	array(
			  array('id' => '1','title' => 'Mega Offer','start_date' => '1630454400','end_date' => '1635638340','status' => '1','featured' => '1','background_color' => '#04517','text_color' => 'dark','banner' => '108','slug' => 'mega-offer-boxuc','created_at' => '2021-09-14 09:54:06','updated_at' => '2021-10-02 01:18:56')
			),
        );
    }
}
