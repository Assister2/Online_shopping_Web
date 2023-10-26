<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipEngineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         \DB::table('ship_engines')->delete();

        \DB::table('ship_engines')->insert(
            [
                ["id" => 1, "name" => "stamps_com", "image" => ""],
            ]
        );   
    }
}
