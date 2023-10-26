<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('notifications')->delete();

        \DB::table('notifications')->insert(
        	array(
			  array('id' => '0360e854-f11b-450c-9a4c-60a104847002','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '29','data' => '{"order_id":6,"order_code":"20211009-01184456","user_id":29,"seller_id":29,"status":"on_the_way"}','read_at' => '2021-10-09 06:58:59','created_at' => '2021-10-09 06:58:04','updated_at' => '2021-10-09 06:58:59'),
			  array('id' => '03ad701e-d21b-46a6-9d7a-0e6ad1bbd6d4','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '45','data' => '{"order_id":24,"order_code":"20211117-10132798","user_id":52,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-17 15:43:27','updated_at' => '2021-11-17 15:43:27'),
			  array('id' => '05a8e945-97d2-4fe7-b1be-5787f8a24705','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '29','data' => '{"order_id":6,"order_code":"20211009-01184456","user_id":29,"seller_id":"29","status":"placed"}','read_at' => '2021-10-09 06:49:26','created_at' => '2021-10-09 06:48:46','updated_at' => '2021-10-09 06:49:26'),
			  array('id' => '0716de0f-7c50-4c4b-8150-5c13e19f3938','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":7,"order_code":"20211009-04375768","user_id":29,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-09 10:07:57','updated_at' => '2021-10-09 10:07:57'),
			  array('id' => '0a188b23-24d4-4712-9543-b5177b84cd8d','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":3,"order_code":"20210928-15381312","user_id":18,"seller_id":"8","status":"placed"}','read_at' => '2021-10-02 01:48:44','created_at' => '2021-09-28 21:08:13','updated_at' => '2021-10-02 01:48:44'),
			  array('id' => '0e7dd006-19e5-40c0-8432-140626d2866c','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":8,"order_code":"20211011-18182198","user_id":9,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-11 23:48:21','updated_at' => '2021-10-11 23:48:21'),
			  array('id' => '1034b542-4034-49b7-af15-903d8474eff8','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":21,"order_code":"20211110-11091282","user_id":48,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-10 16:39:54','updated_at' => '2021-11-10 16:39:54'),
			  array('id' => '1720e278-9a10-41c2-90d2-3366361db8b3','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":20,"order_code":"20211109-13213954","user_id":45,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-09 18:51:40','updated_at' => '2021-11-09 18:51:40'),
			  array('id' => '19abe7f3-e5eb-422f-b2ad-2c7be73dd17e','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":"9","status":"placed"}','read_at' => '2021-10-02 01:48:44','created_at' => '2021-09-27 17:21:19','updated_at' => '2021-10-02 01:48:44'),
			  array('id' => '1a52ee09-0b99-49c1-9db0-2c6536fbcabd','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '16','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":9,"status":"delivered"}','read_at' => NULL,'created_at' => '2021-09-27 17:21:48','updated_at' => '2021-09-27 17:21:48'),
			  array('id' => '1b0e15da-f8a2-4573-87c3-63fd90babe68','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"delivered"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:59','updated_at' => '2021-11-20 08:41:59'),
			  array('id' => '1bd7f026-f149-4a9d-bec4-282537a1c77b','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":17,"order_code":"20211109-12190285","user_id":44,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-09 17:49:04','updated_at' => '2021-11-09 17:49:04'),
			  array('id' => '237c512b-4320-4bbe-8413-e7bb3ae41eac','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '53','data' => '{"order_id":27,"order_code":"20211118-16330158","user_id":53,"seller_id":8,"status":"unpaid"}','read_at' => NULL,'created_at' => '2021-11-18 22:06:58','updated_at' => '2021-11-18 22:06:58'),
			  array('id' => '27eb29b6-56fe-4e11-9e9d-8524bd46e11b','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '33','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"on_the_way"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:50','updated_at' => '2021-11-20 08:41:50'),
			  array('id' => '2a313fef-7cf1-4acc-8150-4d49f51ca0e0','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":4,"order_code":"20211001-23261330","user_id":21,"seller_id":8,"status":"delivered"}','read_at' => '2021-10-07 23:06:32','created_at' => '2021-10-02 05:03:50','updated_at' => '2021-10-07 23:06:32'),
			  array('id' => '2c606d87-dfad-422f-b624-47ff4d898d1b','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":19,"order_code":"20211109-13093051","user_id":45,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-09 18:39:31','updated_at' => '2021-11-09 18:39:31'),
			  array('id' => '2d0496cc-dea5-4ba9-aaeb-aeb3de4d1233','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":18,"order_code":"20211109-13082491","user_id":45,"seller_id":45,"status":"paid"}','read_at' => NULL,'created_at' => '2021-11-09 18:52:15','updated_at' => '2021-11-09 18:52:15'),
			  array('id' => '2e6c2ca3-5e9e-4ad2-9c59-6c2f9c24f50e','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '29','data' => '{"order_id":6,"order_code":"20211009-01184456","user_id":29,"seller_id":29,"status":"confirmed"}','read_at' => '2021-10-09 06:58:59','created_at' => '2021-10-09 06:57:43','updated_at' => '2021-10-09 06:58:59'),
			  array('id' => '31674a1d-3579-46cf-be97-be76591d5a35','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '52','data' => '{"order_id":23,"order_code":"20211117-10131037","user_id":52,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-17 15:43:12','updated_at' => '2021-11-17 15:43:12'),
			  array('id' => '3695947c-9b2f-4e6f-a169-1bb116308dfc','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":14,"order_code":"20211026-23202392","user_id":39,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-27 04:50:23','updated_at' => '2021-10-27 04:50:23'),
			  array('id' => '379e0197-877a-48af-927f-c5d3024c9086','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":25,"order_code":"20211117-10134041","user_id":52,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-17 15:43:41','updated_at' => '2021-11-17 15:43:41'),
			  array('id' => '388fa375-3cb6-4d52-bd76-da8d930c12ae','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":6,"order_code":"20211009-01184456","user_id":29,"seller_id":29,"status":"paid"}','read_at' => NULL,'created_at' => '2021-10-09 06:57:40','updated_at' => '2021-10-09 06:57:40'),
			  array('id' => '3d16abdf-ec86-4fd6-aef7-b1fa5016b186','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '39','data' => '{"order_id":11,"order_code":"20211018-15374484","user_id":39,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-18 21:07:44','updated_at' => '2021-10-18 21:07:44'),
			  array('id' => '3d552313-cc0f-460d-b2f5-51a01d2cd3b1','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '48','data' => '{"order_id":21,"order_code":"20211110-11091282","user_id":48,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-10 16:39:54','updated_at' => '2021-11-10 16:39:54'),
			  array('id' => '3e4adb0c-ac56-459a-96f8-4e352d42d6c5','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":6,"order_code":"20211009-01184456","user_id":29,"seller_id":29,"status":"confirmed"}','read_at' => NULL,'created_at' => '2021-10-09 06:57:43','updated_at' => '2021-10-09 06:57:43'),
			  array('id' => '40864acb-3b3d-4d58-8170-72b465979000','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":9,"status":"delivered"}','read_at' => '2021-10-02 01:48:44','created_at' => '2021-09-27 17:21:48','updated_at' => '2021-10-02 01:48:44'),
			  array('id' => '43363b40-5241-43fa-9897-38ee8d94ca9a','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":27,"order_code":"20211118-16330158","user_id":53,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-18 22:03:01','updated_at' => '2021-11-18 22:03:01'),
			  array('id' => '435ebbc2-6c2e-4a8e-9378-046e35d6c053','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":12,"order_code":"20211024-18151630","user_id":9,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-24 23:45:16','updated_at' => '2021-10-24 23:45:16'),
			  array('id' => '457623cd-f1a8-4139-b96c-02cba03439ea','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '21','data' => '{"order_id":4,"order_code":"20211001-23261330","user_id":21,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-02 04:56:13','updated_at' => '2021-10-02 04:56:13'),
			  array('id' => '460e41b9-5fc7-4275-8fbf-35400ff3a735','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":10,"order_code":"20211018-11501172","user_id":38,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-18 17:20:11','updated_at' => '2021-10-18 17:20:11'),
			  array('id' => '48b5dc1b-2fb2-47ba-933a-e042be41dc59','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":4,"order_code":"20211001-23261330","user_id":21,"seller_id":"8","status":"placed"}','read_at' => '2021-10-07 23:06:32','created_at' => '2021-10-02 04:56:13','updated_at' => '2021-10-07 23:06:32'),
			  array('id' => '48c8ad02-b566-4adc-986c-eed4bd56740c','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":11,"order_code":"20211018-15374484","user_id":39,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-18 21:07:44','updated_at' => '2021-10-18 21:07:44'),
			  array('id' => '49e0d085-9536-4dfb-9218-15f33d1e9299','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":9,"order_code":"20211011-20283796","user_id":34,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-12 01:58:37','updated_at' => '2021-10-12 01:58:37'),
			  array('id' => '4c1da8a5-0422-4dfa-986e-e67114f7907f','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":27,"order_code":"20211118-16330158","user_id":53,"seller_id":8,"status":"unpaid"}','read_at' => NULL,'created_at' => '2021-11-18 22:06:58','updated_at' => '2021-11-18 22:06:58'),
			  array('id' => '5051213a-29aa-442e-9350-494d28edb7b3','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":3,"order_code":"20210928-15381312","user_id":18,"seller_id":8,"status":"delivered"}','read_at' => '2021-10-02 01:48:44','created_at' => '2021-09-28 21:08:41','updated_at' => '2021-10-02 01:48:44'),
			  array('id' => '54b65046-320c-49e1-9de3-d37cf8115659','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '38','data' => '{"order_id":10,"order_code":"20211018-11501172","user_id":38,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-18 17:20:11','updated_at' => '2021-10-18 17:20:11'),
			  array('id' => '558d5052-96d9-466a-99e1-aa97c38a961d','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '52','data' => '{"order_id":24,"order_code":"20211117-10132798","user_id":52,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-17 15:43:27','updated_at' => '2021-11-17 15:43:27'),
			  array('id' => '55f1b3a6-15c4-4872-816b-878306c70359','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":9,"status":"unpaid"}','read_at' => NULL,'created_at' => '2021-10-24 23:47:03','updated_at' => '2021-10-24 23:47:03'),
			  array('id' => '571ac0e0-55e8-4c00-b73b-513f1b2adb37','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"paid"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:12','updated_at' => '2021-11-20 08:41:12'),
			  array('id' => '5756e7d9-b91a-434d-910f-26f8bec27ad5','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '33','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"picked_up"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:39','updated_at' => '2021-11-20 08:41:39'),
			  array('id' => '5e4a39fd-e892-4fe5-ab21-f775a47b2797','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '53','data' => '{"order_id":27,"order_code":"20211118-16330158","user_id":53,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-18 22:03:01','updated_at' => '2021-11-18 22:03:01'),
			  array('id' => '62a34673-8630-4536-a86a-44d0a526d4ad','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '53','data' => '{"order_id":27,"order_code":"20211118-16330158","user_id":53,"seller_id":8,"status":"paid"}','read_at' => NULL,'created_at' => '2021-11-20 08:40:14','updated_at' => '2021-11-20 08:40:14'),
			  array('id' => '66afbad0-8b2f-4724-80e5-b401f0fd165d','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '52','data' => '{"order_id":25,"order_code":"20211117-10134041","user_id":52,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-17 15:43:41','updated_at' => '2021-11-17 15:43:41'),
			  array('id' => '698d49df-2521-4664-aea2-775d8e21c1b6','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '45','data' => '{"order_id":19,"order_code":"20211109-13093051","user_id":45,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-09 18:39:31','updated_at' => '2021-11-09 18:39:31'),
			  array('id' => '7306a686-4968-4410-bcc3-dd2f7af32498','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":5,"order_code":"20211007-07441932","user_id":23,"seller_id":"8","status":"placed"}','read_at' => '2021-10-07 23:06:32','created_at' => '2021-10-07 13:14:19','updated_at' => '2021-10-07 23:06:32'),
			  array('id' => '7565772b-c85a-41a7-857a-d9bad338c3a9','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":21,"order_code":"20211110-11091282","user_id":48,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-10 16:39:54','updated_at' => '2021-11-10 16:39:54'),
			  array('id' => '759232e8-1c63-4e9f-8b42-13fa926be280','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"delivered"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:59','updated_at' => '2021-11-20 08:41:59'),
			  array('id' => '766578e2-d543-4354-987b-b342d8de4d05','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":9,"status":"unpaid"}','read_at' => NULL,'created_at' => '2021-10-24 23:47:03','updated_at' => '2021-10-24 23:47:03'),
			  array('id' => '769fbfe8-15de-4e64-a0bb-44aed1e7d53d','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '44','data' => '{"order_id":17,"order_code":"20211109-12190285","user_id":44,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-09 17:49:04','updated_at' => '2021-11-09 17:49:04'),
			  array('id' => '7f4e3fc0-d0aa-4713-9b74-38d689f5292d','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":1,"order_code":"20210922-07073940","user_id":9,"seller_id":"9","status":"placed"}','read_at' => '2021-10-02 01:48:44','created_at' => '2021-09-22 07:07:39','updated_at' => '2021-10-02 01:48:44'),
			  array('id' => '8278364c-ea7d-4bba-a156-478e267966d6','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '16','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-09-27 17:21:19','updated_at' => '2021-09-27 17:21:19'),
			  array('id' => '88407a83-a648-429c-993d-903d13a3e6f0','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '34','data' => '{"order_id":9,"order_code":"20211011-20283796","user_id":34,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-12 01:58:37','updated_at' => '2021-10-12 01:58:37'),
			  array('id' => '8a042ab2-81e4-41bf-9670-f8f40ce120cd','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":22,"order_code":"20211110-11101923","user_id":48,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-10 16:40:29','updated_at' => '2021-11-10 16:40:29'),
			  array('id' => '8af394da-d50a-4ccb-a0e8-a613a0ff60bf','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '48','data' => '{"order_id":22,"order_code":"20211110-11101923","user_id":48,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-10 16:40:29','updated_at' => '2021-11-10 16:40:29'),
			  array('id' => '8ba05a26-2286-4c81-9380-a7d7983b6f86','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"picked_up"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:39','updated_at' => '2021-11-20 08:41:39'),
			  array('id' => '8d718b89-1d6c-4973-b535-1d27e7ee2e5b','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"paid"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:12','updated_at' => '2021-11-20 08:41:12'),
			  array('id' => '931a4d85-5105-4c62-a16e-17ebc902015a','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":6,"order_code":"20211009-01184456","user_id":29,"seller_id":29,"status":"on_the_way"}','read_at' => NULL,'created_at' => '2021-10-09 06:58:04','updated_at' => '2021-10-09 06:58:04'),
			  array('id' => '94ec0487-ae1d-4ec2-ae66-83884b062106','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '45','data' => '{"order_id":23,"order_code":"20211117-10131037","user_id":52,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-17 15:43:12','updated_at' => '2021-11-17 15:43:12'),
			  array('id' => '9594d5c3-6a5e-4e78-9c56-8b4dbf589762','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '16','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":9,"status":"unpaid"}','read_at' => NULL,'created_at' => '2021-10-24 23:47:03','updated_at' => '2021-10-24 23:47:03'),
			  array('id' => '95ccf86b-898d-48fc-8135-fe12f063cfb1','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '45','data' => '{"order_id":25,"order_code":"20211117-10134041","user_id":52,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-17 15:43:41','updated_at' => '2021-11-17 15:43:41'),
			  array('id' => '95f5aaaa-e311-43e8-a42b-023397328220','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '23','data' => '{"order_id":5,"order_code":"20211007-07441932","user_id":23,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-07 13:14:20','updated_at' => '2021-10-07 13:14:20'),
			  array('id' => '988f9180-db77-456e-bee6-88cfd7ca051c','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":9,"status":"delivered"}','read_at' => NULL,'created_at' => '2021-09-27 17:21:48','updated_at' => '2021-09-27 17:21:48'),
			  array('id' => '9d6042a2-5d2a-4d22-9ddd-26823fb23835','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '33','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"delivered"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:59','updated_at' => '2021-11-20 08:41:59'),
			  array('id' => '9dd84f12-5d51-438d-9ab0-05461bec2b8e','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '16','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":9,"status":"paid"}','read_at' => NULL,'created_at' => '2021-09-27 17:22:40','updated_at' => '2021-09-27 17:22:40'),
			  array('id' => 'a1f5ffaf-ae83-4522-8bbe-0b691b53ec02','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":9,"status":"paid"}','read_at' => NULL,'created_at' => '2021-09-27 17:22:40','updated_at' => '2021-09-27 17:22:40'),
			  array('id' => 'ad79cbee-92c9-453a-b736-e821b2299002','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"on_the_way"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:50','updated_at' => '2021-11-20 08:41:50'),
			  array('id' => 'b10bdfe6-1328-4543-8764-e36c0ec665ff','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":1,"order_code":"20210922-07073940","user_id":9,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-09-22 07:07:39','updated_at' => '2021-09-22 07:07:39'),
			  array('id' => 'b33b4cf5-b484-4c4d-8fe1-1cfbfd7b56ce','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '18','data' => '{"order_id":3,"order_code":"20210928-15381312","user_id":18,"seller_id":8,"status":"delivered"}','read_at' => NULL,'created_at' => '2021-09-28 21:08:41','updated_at' => '2021-09-28 21:08:41'),
			  array('id' => 'b3ed98cf-0600-4707-8fff-52560c4135c8','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":18,"order_code":"20211109-13082491","user_id":45,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-09 18:38:25','updated_at' => '2021-11-09 18:38:25'),
			  array('id' => 'b7231d35-f5b4-4a07-84e2-14bbfae784ec','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":27,"order_code":"20211118-16330158","user_id":53,"seller_id":8,"status":"unpaid"}','read_at' => NULL,'created_at' => '2021-11-20 08:40:44','updated_at' => '2021-11-20 08:40:44'),
			  array('id' => 'c1a52d09-2cc4-417a-a05f-e64d455e6a0a','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"on_the_way"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:50','updated_at' => '2021-11-20 08:41:50'),
			  array('id' => 'c3412134-bd92-4d6b-8b13-52a616e480ed','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":27,"order_code":"20211118-16330158","user_id":53,"seller_id":8,"status":"delivered"}','read_at' => NULL,'created_at' => '2021-11-18 22:07:05','updated_at' => '2021-11-18 22:07:05'),
			  array('id' => 'c36f8779-816f-491a-9e9a-af6d69fc1a28','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '33','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"paid"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:12','updated_at' => '2021-11-20 08:41:12'),
			  array('id' => 'c3bd659a-8e21-4cb8-af90-5bb76a234285','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '53','data' => '{"order_id":27,"order_code":"20211118-16330158","user_id":53,"seller_id":8,"status":"unpaid"}','read_at' => NULL,'created_at' => '2021-11-20 08:40:44','updated_at' => '2021-11-20 08:40:44'),
			  array('id' => 'c4dd0f8e-2fb2-4d4f-89bd-fe361c5424bc','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":8,"order_code":"20211011-18182198","user_id":9,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-11 23:48:21','updated_at' => '2021-10-11 23:48:21'),
			  array('id' => 'cadb5c0f-56f9-4c95-8076-a8a40a95d20f','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":22,"order_code":"20211110-11101923","user_id":48,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-10 16:40:29','updated_at' => '2021-11-10 16:40:29'),
			  array('id' => 'cb8a790f-e8d9-45ed-9d84-6b8a2e4a37e2','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-09-27 17:21:19','updated_at' => '2021-09-27 17:21:19'),
			  array('id' => 'd5f89bc6-f79e-4305-9f40-bfb5acbc0059','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '29','data' => '{"order_id":6,"order_code":"20211009-01184456","user_id":29,"seller_id":29,"status":"paid"}','read_at' => '2021-10-09 06:58:59','created_at' => '2021-10-09 06:57:40','updated_at' => '2021-10-09 06:58:59'),
			  array('id' => 'd613781c-0793-4580-bfd9-1de9877c915e','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":27,"order_code":"20211118-16330158","user_id":53,"seller_id":8,"status":"paid"}','read_at' => NULL,'created_at' => '2021-11-20 08:40:14','updated_at' => '2021-11-20 08:40:14'),
			  array('id' => 'd693cf98-f03a-47cd-afd9-5ce2089fa3c4','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '39','data' => '{"order_id":14,"order_code":"20211026-23202392","user_id":39,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-27 04:50:23','updated_at' => '2021-10-27 04:50:23'),
			  array('id' => 'da5e6899-7dd2-4fe5-a421-506884c57db7','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":6,"order_code":"20211009-01184456","user_id":29,"seller_id":"29","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-09 06:48:46','updated_at' => '2021-10-09 06:48:46'),
			  array('id' => 'db2e8371-8e7c-4ad5-89ba-15ddfe94e24c','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '21','data' => '{"order_id":4,"order_code":"20211001-23261330","user_id":21,"seller_id":8,"status":"delivered"}','read_at' => NULL,'created_at' => '2021-10-02 05:03:50','updated_at' => '2021-10-02 05:03:50'),
			  array('id' => 'ded0de0f-b835-44de-9ad4-58beaddbdd54','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '18','data' => '{"order_id":3,"order_code":"20210928-15381312","user_id":18,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-09-28 21:08:13','updated_at' => '2021-09-28 21:08:13'),
			  array('id' => 'df27a01d-ff97-4d8f-9fc1-16e0db41016c','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '53','data' => '{"order_id":27,"order_code":"20211118-16330158","user_id":53,"seller_id":8,"status":"delivered"}','read_at' => NULL,'created_at' => '2021-11-18 22:07:05','updated_at' => '2021-11-18 22:07:05'),
			  array('id' => 'dfb0d5fa-ccb7-4357-8fda-ac101db751da','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '45','data' => '{"order_id":18,"order_code":"20211109-13082491","user_id":45,"seller_id":45,"status":"paid"}','read_at' => NULL,'created_at' => '2021-11-09 18:52:15','updated_at' => '2021-11-09 18:52:15'),
			  array('id' => 'e2823cde-8104-438e-87e6-4c2a89302fda','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":24,"order_code":"20211117-10132798","user_id":52,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-17 15:43:27','updated_at' => '2021-11-17 15:43:27'),
			  array('id' => 'e74b4192-7937-4fb5-a6a5-3fd92417b277','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":2,"order_code":"20210927-11511635","user_id":16,"seller_id":9,"status":"paid"}','read_at' => '2021-10-02 01:48:44','created_at' => '2021-09-27 17:22:40','updated_at' => '2021-10-02 01:48:44'),
			  array('id' => 'e9883afd-50b0-4dc3-844f-bc064a278778','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":17,"order_code":"20211109-12190285","user_id":44,"seller_id":"9","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-09 17:49:04','updated_at' => '2021-11-09 17:49:04'),
			  array('id' => 'ecd0ffb1-a68c-4208-888d-697f3ddd7ebe','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '45','data' => '{"order_id":20,"order_code":"20211109-13213954","user_id":45,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-09 18:51:40','updated_at' => '2021-11-09 18:51:40'),
			  array('id' => 'edbb75bf-774d-4d26-8314-af9f41e6bae0','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":12,"order_code":"20211024-18151630","user_id":9,"seller_id":"8","status":"placed"}','read_at' => NULL,'created_at' => '2021-10-24 23:45:16','updated_at' => '2021-10-24 23:45:16'),
			  array('id' => 'f32ef3db-758c-4c37-b355-2d827eebbbe9','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":23,"order_code":"20211117-10131037","user_id":52,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-17 15:43:12','updated_at' => '2021-11-17 15:43:12'),
			  array('id' => 'f5769f2a-5e2a-4f28-823d-c5000a4c2c70','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '8','data' => '{"order_id":18,"order_code":"20211109-13082491","user_id":45,"seller_id":45,"status":"delivered"}','read_at' => NULL,'created_at' => '2021-11-09 18:52:18','updated_at' => '2021-11-09 18:52:18'),
			  array('id' => 'fa5cfb9d-2e3e-4343-95da-7d57598ba66a','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '9','data' => '{"order_id":26,"order_code":"20211117-102724","user_id":33,"seller_id":9,"status":"picked_up"}','read_at' => NULL,'created_at' => '2021-11-20 08:41:39','updated_at' => '2021-11-20 08:41:39'),
			  array('id' => 'fac61d65-b586-4a82-b371-f56a46106753','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '45','data' => '{"order_id":18,"order_code":"20211109-13082491","user_id":45,"seller_id":"45","status":"placed"}','read_at' => NULL,'created_at' => '2021-11-09 18:38:25','updated_at' => '2021-11-09 18:38:25'),
			  array('id' => 'fd958c85-7d93-42c9-b8ec-e75cd6558f1f','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '45','data' => '{"order_id":18,"order_code":"20211109-13082491","user_id":45,"seller_id":45,"status":"delivered"}','read_at' => NULL,'created_at' => '2021-11-09 18:52:18','updated_at' => '2021-11-09 18:52:18'),
			  array('id' => 'fe20ca73-b898-4182-b0c6-b97c545216e4','type' => 'App\\Notifications\\OrderNotification','notifiable_type' => 'App\\User','notifiable_id' => '29','data' => '{"order_id":7,"order_code":"20211009-04375768","user_id":29,"seller_id":"8","status":"placed"}','read_at' => '2021-10-09 10:08:10','created_at' => '2021-10-09 10:07:57','updated_at' => '2021-10-09 10:08:10')
			),
        );
    }
}