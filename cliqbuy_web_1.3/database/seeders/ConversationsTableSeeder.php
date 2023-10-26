<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('conversations')->delete();

        \DB::table('conversations')->insert(
			array(
			  array('id' => '1','sender_id' => '33','receiver_id' => '9','title' => 'Kids dress','sender_viewed' => '1','receiver_viewed' => '1','created_at' => '2021-10-25 23:50:21','updated_at' => '2021-10-26 03:25:14'),
			  array('id' => '2','sender_id' => '33','receiver_id' => '8','title' => 'wireless headphone','sender_viewed' => '1','receiver_viewed' => '0','created_at' => '2021-11-20 02:57:44','updated_at' => '2021-11-20 02:57:44'),
			  array('id' => '3','sender_id' => '33','receiver_id' => '8','title' => 'wireless headphone','sender_viewed' => '1','receiver_viewed' => '0','created_at' => '2021-11-20 02:57:48','updated_at' => '2021-11-20 02:57:48'),
			  array('id' => '4','sender_id' => '33','receiver_id' => '8','title' => 'T-shirt','sender_viewed' => '1','receiver_viewed' => '0','created_at' => '2021-11-20 17:12:01','updated_at' => '2021-11-20 17:12:01'),
			  array('id' => '5','sender_id' => '55','receiver_id' => '9','title' => 'Kids dress','sender_viewed' => '1','receiver_viewed' => '1','created_at' => '2021-11-24 00:17:49','updated_at' => '2021-11-24 00:22:10'),
			  array('id' => '6','sender_id' => '55','receiver_id' => '9','title' => 'Kids dress','sender_viewed' => '1','receiver_viewed' => '0','created_at' => '2021-11-24 00:17:50','updated_at' => '2021-11-24 00:17:50'),
			  array('id' => '7','sender_id' => '55','receiver_id' => '9','title' => 'Kids dress','sender_viewed' => '1','receiver_viewed' => '0','created_at' => '2021-11-24 00:17:51','updated_at' => '2021-11-24 00:17:51'),
			  array('id' => '8','sender_id' => '55','receiver_id' => '9','title' => 'Kids dress','sender_viewed' => '1','receiver_viewed' => '1','created_at' => '2021-11-24 00:18:22','updated_at' => '2021-11-24 00:19:08'),
			  array('id' => '9','sender_id' => '55','receiver_id' => '8','title' => 'Computer table','sender_viewed' => '1','receiver_viewed' => '1','created_at' => '2021-11-24 01:00:37','updated_at' => '2021-11-24 01:00:46')
			),
        );
    }
}
