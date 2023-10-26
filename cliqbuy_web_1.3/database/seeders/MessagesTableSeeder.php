<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('messages')->delete();

        \DB::table('messages')->insert(
        	array(
			  array('id' => '1','conversation_id' => '1','user_id' => '33','message' => 'tst','created_at' => '2021-10-25 23:50:21','updated_at' => '2021-10-25 23:50:21'),
			  array('id' => '2','conversation_id' => '1','user_id' => '33','message' => 'Hi','created_at' => '2021-10-26 03:25:14','updated_at' => '2021-10-26 03:25:14'),
			  array('id' => '3','conversation_id' => '1','user_id' => '33','message' => 'heyyy','created_at' => '2021-11-04 16:49:43','updated_at' => '2021-11-04 16:49:43'),
			  array('id' => '4','conversation_id' => '1','user_id' => '33','message' => 'ucg','created_at' => '2021-11-04 18:39:29','updated_at' => '2021-11-04 18:39:29'),
			  array('id' => '5','conversation_id' => '1','user_id' => '33','message' => 'snsjd','created_at' => '2021-11-20 01:17:01','updated_at' => '2021-11-20 01:17:01'),
			  array('id' => '6','conversation_id' => '1','user_id' => '33','message' => 'hi','created_at' => '2021-11-20 02:52:06','updated_at' => '2021-11-20 02:52:06'),
			  array('id' => '7','conversation_id' => '1','user_id' => '33','message' => 'heloo','created_at' => '2021-11-20 02:52:17','updated_at' => '2021-11-20 02:52:17'),
			  array('id' => '8','conversation_id' => '1','user_id' => '33','message' => 'a','created_at' => '2021-11-20 02:52:26','updated_at' => '2021-11-20 02:52:26'),
			  array('id' => '9','conversation_id' => '1','user_id' => '33','message' => '.','created_at' => '2021-11-20 02:52:32','updated_at' => '2021-11-20 02:52:32'),
			  array('id' => '10','conversation_id' => '2','user_id' => '33','message' => 'hi','created_at' => '2021-11-20 02:57:44','updated_at' => '2021-11-20 02:57:44'),
			  array('id' => '11','conversation_id' => '3','user_id' => '33','message' => 'hi','created_at' => '2021-11-20 02:57:48','updated_at' => '2021-11-20 02:57:48'),
			  array('id' => '12','conversation_id' => '4','user_id' => '33','message' => 'how are you','created_at' => '2021-11-20 17:12:01','updated_at' => '2021-11-20 17:12:01'),
			  array('id' => '13','conversation_id' => '5','user_id' => '55','message' => 'hi','created_at' => '2021-11-24 00:17:49','updated_at' => '2021-11-24 00:17:49'),
			  array('id' => '14','conversation_id' => '6','user_id' => '55','message' => 'hi','created_at' => '2021-11-24 00:17:50','updated_at' => '2021-11-24 00:17:50'),
			  array('id' => '15','conversation_id' => '7','user_id' => '55','message' => 'hi','created_at' => '2021-11-24 00:17:51','updated_at' => '2021-11-24 00:17:51'),
			  array('id' => '16','conversation_id' => '8','user_id' => '55','message' => 'hi','created_at' => '2021-11-24 00:18:22','updated_at' => '2021-11-24 00:18:22'),
			  array('id' => '17','conversation_id' => '8','user_id' => '55','message' => 'hello','created_at' => '2021-11-24 00:19:08','updated_at' => '2021-11-24 00:19:08'),
			  array('id' => '18','conversation_id' => '8','user_id' => '55','message' => 'welcome','created_at' => '2021-11-24 00:19:15','updated_at' => '2021-11-24 00:19:15'),
			  array('id' => '19','conversation_id' => '8','user_id' => '55','message' => 'new','created_at' => '2021-11-24 00:19:21','updated_at' => '2021-11-24 00:19:21'),
			  array('id' => '20','conversation_id' => '8','user_id' => '55','message' => 'hi','created_at' => '2021-11-24 00:19:29','updated_at' => '2021-11-24 00:19:29'),
			  array('id' => '21','conversation_id' => '8','user_id' => '55','message' => 'good','created_at' => '2021-11-24 00:19:44','updated_at' => '2021-11-24 00:19:44'),
			  array('id' => '22','conversation_id' => '8','user_id' => '55','message' => 'hi','created_at' => '2021-11-24 00:19:52','updated_at' => '2021-11-24 00:19:52'),
			  array('id' => '23','conversation_id' => '8','user_id' => '55','message' => 'g','created_at' => '2021-11-24 00:19:59','updated_at' => '2021-11-24 00:19:59'),
			  array('id' => '24','conversation_id' => '8','user_id' => '55','message' => 'great','created_at' => '2021-11-24 00:20:19','updated_at' => '2021-11-24 00:20:19'),
			  array('id' => '25','conversation_id' => '8','user_id' => '55','message' => 'hi','created_at' => '2021-11-24 00:21:48','updated_at' => '2021-11-24 00:21:48'),
			  array('id' => '26','conversation_id' => '8','user_id' => '55','message' => 'jjj','created_at' => '2021-11-24 00:21:53','updated_at' => '2021-11-24 00:21:53'),
			  array('id' => '27','conversation_id' => '8','user_id' => '55','message' => 'hh','created_at' => '2021-11-24 00:21:58','updated_at' => '2021-11-24 00:21:58'),
			  array('id' => '28','conversation_id' => '5','user_id' => '55','message' => 'bbb','created_at' => '2021-11-24 00:22:10','updated_at' => '2021-11-24 00:22:10'),
			  array('id' => '29','conversation_id' => '8','user_id' => '55','message' => 'djjf','created_at' => '2021-11-24 00:22:18','updated_at' => '2021-11-24 00:22:18'),
			  array('id' => '30','conversation_id' => '9','user_id' => '55','message' => 'hi','created_at' => '2021-11-24 01:00:37','updated_at' => '2021-11-24 01:00:37'),
			  array('id' => '31','conversation_id' => '9','user_id' => '55','message' => 'hello','created_at' => '2021-11-24 01:00:46','updated_at' => '2021-11-24 01:00:46')
			),
        );
    }
}
