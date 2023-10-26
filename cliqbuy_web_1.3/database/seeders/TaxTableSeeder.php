<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class TaxTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('taxes')->delete();

        \DB::table('taxes')->insert(
        	array(
			  	array('id' => '3','name' => 'Tax','tax_status' => '1','created_at' => '2021-03-07 17:15:33','updated_at' => '2021-11-14 00:16:37'),
			  	array('id' => '4','name' => 'GST 28%','tax_status' => '1','created_at' => '2021-11-14 00:16:27','updated_at' => '2021-11-14 00:16:27'),
			  	array('id' => '5','name' => 'GST 28%','tax_status' => '1','created_at' => '2021-11-14 00:16:30','updated_at' => '2021-11-14 00:16:30'),
			  	array('id' => '6','name' => 'GST 28%','tax_status' => '1','created_at' => '2021-11-14 00:16:34','updated_at' => '2021-11-14 00:16:34')
			),
        );
    }
}
