<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->delete();

        \DB::table('roles')->insert(
        	array(
			  	array('id' => '1','name' => 'Manager','permissions' => '["2","3","4","5","8","9","10","11","14","20"]','created_at' => '2018-10-10 10:09:47','updated_at' => '2021-11-24 00:44:24'),
			  	array('id' => '2','name' => 'Accountant','permissions' => '["2","3"]','created_at' => '2018-10-10 10:22:09','updated_at' => '2018-10-10 10:22:09')
			),
        );
    }
}
