<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('customers')->delete();

        \DB::table('customers')->insert(
        	array(
			  	
			  	array('id' => '4','user_id' => '8','created_at' => '2019-08-01 16:05:09','updated_at' => '2019-08-01 16:05:09'),
			  	array('id' => '6','user_id' => '13','created_at' => '2021-09-18 06:52:42','updated_at' => '2021-09-18 06:52:42'),
			  	array('id' => '7','user_id' => '14','created_at' => '2021-09-22 05:01:40','updated_at' => '2021-09-22 05:01:40'),
			  	array('id' => '8','user_id' => '15','created_at' => '2021-09-23 10:56:00','updated_at' => '2021-09-23 10:56:00'),
			  	array('id' => '9','user_id' => '16','created_at' => '2021-09-27 17:19:38','updated_at' => '2021-09-27 17:19:38'),
			  	array('id' => '12','user_id' => '21','created_at' => '2021-10-02 04:54:50','updated_at' => '2021-10-02 04:54:50')	,
			  	array('id' => '13','user_id' => '23','created_at' => '2021-10-07 13:11:17','updated_at' => '2021-10-07 13:11:17'),
			  	array('id' => '14','user_id' => '27','created_at' => '2021-10-08 16:50:27','updated_at' => '2021-10-08 16:50:27'),
			  	array('id' => '15','user_id' => '32','created_at' => '2021-10-11 06:44:37','updated_at' => '2021-10-11 06:44:37'),
			  	array('id' => '16','user_id' => '33','created_at' => '2021-10-12 01:08:06','updated_at' => '2021-10-12 01:08:06'),
			  	array('id' => '17','user_id' => '34','created_at' => '2021-10-12 01:51:34','updated_at' => '2021-10-12 01:51:34'),
				array('id' => '18','user_id' => '35','created_at' => '2021-10-12 20:32:20','updated_at' => '2021-10-12 20:32:20'),
			  	array('id' => '19','user_id' => '37','created_at' => '2021-10-14 14:43:50','updated_at' => '2021-10-14 14:43:50'),
			  	array('id' => '20','user_id' => '38','created_at' => '2021-10-18 17:07:49','updated_at' => '2021-10-18 17:07:49'),
			  	array('id' => '21','user_id' => '39','created_at' => '2021-10-18 21:05:47','updated_at' => '2021-10-18 21:05:47'),
			  	array('id' => '22','user_id' => '40','created_at' => '2021-11-06 15:50:36','updated_at' => '2021-11-06 15:50:36'),
			  	array('id' => '23','user_id' => '41','created_at' => '2021-11-07 16:21:43','updated_at' => '2021-11-07 16:21:43'),
			  	array('id' => '24','user_id' => '42','created_at' => '2021-11-09 04:23:06','updated_at' => '2021-11-09 04:23:06'),
			  	array('id' => '25','user_id' => '43','created_at' => '2021-11-09 04:29:01','updated_at' => '2021-11-09 04:29:01'),
			  	array('id' => '26','user_id' => '44','created_at' => '2021-11-09 17:38:29','updated_at' => '2021-11-09 17:38:29'),
			  	array('id' => '27','user_id' => '46','created_at' => '2021-11-10 01:39:02','updated_at' => '2021-11-10 01:39:02'),
			  	array('id' => '28','user_id' => '47','created_at' => '2021-11-10 01:49:53','updated_at' => '2021-11-10 01:49:53'),
			  	array('id' => '29','user_id' => '48','created_at' => '2021-11-10 16:36:02','updated_at' => '2021-11-10 16:36:02'),
			  	array('id' => '31','user_id' => '50','created_at' => '2021-11-14 13:52:58','updated_at' => '2021-11-14 13:52:58'),
			  	array('id' => '32','user_id' => '52','created_at' => '2021-11-17 15:41:57','updated_at' => '2021-11-17 15:41:57'),
			  	array('id' => '33','user_id' => '53','created_at' => '2021-11-18 21:58:52','updated_at' => '2021-11-18 21:58:52'),
			  	array('id' => '34','user_id' => '54','created_at' => '2021-11-18 22:27:01','updated_at' => '2021-11-18 22:27:01'),
			  	array('id' => '35','user_id' => '55','created_at' => '2021-11-24 00:07:33','updated_at' => '2021-11-24 00:07:33'),
			  	array('id' => '36','user_id' => '56','created_at' => '2021-11-24 00:48:42','updated_at' => '2021-11-24 00:48:42')
			),
        );
    }
}
