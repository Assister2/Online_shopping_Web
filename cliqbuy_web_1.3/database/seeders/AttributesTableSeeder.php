<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('attributes')->delete();

        \DB::table('attributes')->insert(
        	array(
			  	array('id' => '1','name' => 'Size','created_at' => '2020-02-24 11:25:07','updated_at' => '2020-02-24 11:25:07'),
			  	array('id' => '2','name' => 'Fabric','created_at' => '2020-02-24 11:25:13','updated_at' => '2020-02-24 11:25:13')
			),
        );
    }
}
