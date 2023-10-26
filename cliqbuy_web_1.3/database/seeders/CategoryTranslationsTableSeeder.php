<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('category_translations')->delete();

        \DB::table('category_translations')->insert(
        	array(
			  array('id' => '1','category_id' => '1','name' => 'Clothes','lang' => 'en','created_at' => '2021-09-14 06:35:46','updated_at' => '2021-09-14 06:35:46'),
			  array('id' => '2','category_id' => '2','name' => 'Laptop and Accessories','lang' => 'en','created_at' => '2021-09-14 06:38:09','updated_at' => '2021-09-14 09:26:15'),
			  array('id' => '3','category_id' => '3','name' => 'Electronics','lang' => 'en','created_at' => '2021-09-14 06:40:30','updated_at' => '2021-09-14 06:40:30'),
			  array('id' => '4','category_id' => '4','name' => 'Mobiles','lang' => 'en','created_at' => '2021-09-14 06:43:27','updated_at' => '2021-09-14 06:43:27'),
			  array('id' => '5','category_id' => '5','name' => 'Male','lang' => 'en','created_at' => '2021-09-15 08:10:54','updated_at' => '2021-09-15 08:10:54'),
			  array('id' => '6','category_id' => '6','name' => 'Female','lang' => 'en','created_at' => '2021-09-15 08:11:18','updated_at' => '2021-09-15 08:11:18'),
			  array('id' => '7','category_id' => '7','name' => 'Jeans','lang' => 'en','created_at' => '2021-09-15 08:11:37','updated_at' => '2021-09-15 08:11:37'),
			  array('id' => '8','category_id' => '8','name' => 'Jeans','lang' => 'en','created_at' => '2021-09-15 08:16:27','updated_at' => '2021-09-15 08:16:27'),
			  array('id' => '9','category_id' => '9','name' => 'Formal suit','lang' => 'en','created_at' => '2021-09-15 08:16:46','updated_at' => '2021-09-15 08:16:46'),
			  array('id' => '10','category_id' => '10','name' => 'Headset','lang' => 'en','created_at' => '2021-09-15 08:17:25','updated_at' => '2021-09-15 08:17:25'),
			  array('id' => '11','category_id' => '11','name' => 'Foot Wear','lang' => 'en','created_at' => '2021-09-15 09:28:21','updated_at' => '2021-09-15 09:28:21'),
			  array('id' => '12','category_id' => '12','name' => 'Slipper','lang' => 'en','created_at' => '2021-09-15 09:28:43','updated_at' => '2021-09-15 09:28:43'),
			  array('id' => '13','category_id' => '13','name' => 'Casual Shoe','lang' => 'en','created_at' => '2021-09-15 09:29:02','updated_at' => '2021-09-15 09:29:02'),
			  array('id' => '14','category_id' => '14','name' => 'Formal Shoe','lang' => 'en','created_at' => '2021-09-15 09:29:27','updated_at' => '2021-09-15 09:29:27'),
			  array('id' => '15','category_id' => '15','name' => 'Kurties','lang' => 'en','created_at' => '2021-09-15 09:30:07','updated_at' => '2021-09-15 09:30:07'),
			  array('id' => '16','category_id' => '16','name' => 'Chudithar','lang' => 'en','created_at' => '2021-09-15 09:30:28','updated_at' => '2021-09-15 09:30:40'),
			  array('id' => '17','category_id' => '17','name' => 'Leggings','lang' => 'en','created_at' => '2021-09-15 09:32:00','updated_at' => '2021-09-15 09:32:00'),
			  array('id' => '18','category_id' => '18','name' => 'Sarees','lang' => 'en','created_at' => '2021-09-15 09:32:18','updated_at' => '2021-09-15 09:32:18'),
			  array('id' => '19','category_id' => '19','name' => 'Intel','lang' => 'en','created_at' => '2021-09-15 09:33:19','updated_at' => '2021-09-15 09:33:19'),
			  array('id' => '20','category_id' => '20','name' => 'AMD','lang' => 'en','created_at' => '2021-09-15 09:33:38','updated_at' => '2021-09-15 09:33:38'),
			  array('id' => '21','category_id' => '21','name' => 'AMD Ryzen','lang' => 'en','created_at' => '2021-09-15 09:33:50','updated_at' => '2021-09-15 09:33:50'),
			  array('id' => '22','category_id' => '22','name' => 'Intel i3 7th Gen','lang' => 'en','created_at' => '2021-09-15 09:35:23','updated_at' => '2021-09-15 09:35:23'),
			  array('id' => '23','category_id' => '23','name' => 'intel i3 8th Gen','lang' => 'en','created_at' => '2021-09-15 09:35:42','updated_at' => '2021-09-15 09:35:42'),
			  array('id' => '24','category_id' => '24','name' => 'Intel i3 9th Gen','lang' => 'en','created_at' => '2021-09-15 09:36:53','updated_at' => '2021-09-15 09:36:53'),
			  array('id' => '25','category_id' => '25','name' => 'Intel i3 10th Gen','lang' => 'en','created_at' => '2021-09-15 09:37:13','updated_at' => '2021-09-15 09:37:13'),
			  array('id' => '26','category_id' => '26','name' => 'Intel i3 11th Gen','lang' => 'en','created_at' => '2021-09-15 09:37:31','updated_at' => '2021-09-15 09:37:31'),
			  array('id' => '27','category_id' => '27','name' => 'Intel i5 10th Gen','lang' => 'en','created_at' => '2021-09-15 09:40:07','updated_at' => '2021-09-15 09:40:07'),
			  array('id' => '28','category_id' => '28','name' => 'Intel i5 11th Gen','lang' => 'en','created_at' => '2021-09-15 09:40:39','updated_at' => '2021-09-15 09:40:39'),
			  array('id' => '29','category_id' => '29','name' => 'Pentium','lang' => 'en','created_at' => '2021-09-15 09:45:18','updated_at' => '2021-09-15 09:45:18'),
			  array('id' => '30','category_id' => '30','name' => 'Ryzen 2','lang' => 'en','created_at' => '2021-09-15 09:45:38','updated_at' => '2021-09-15 09:45:38'),
			  array('id' => '31','category_id' => '31','name' => 'Ryzen 3','lang' => 'en','created_at' => '2021-09-15 09:45:57','updated_at' => '2021-09-15 09:45:57'),
			  array('id' => '32','category_id' => '32','name' => 'Ryzen 5','lang' => 'en','created_at' => '2021-09-15 09:46:46','updated_at' => '2021-09-15 09:46:46'),
			  array('id' => '33','category_id' => '33','name' => 'Ryzen 7','lang' => 'en','created_at' => '2021-09-15 09:47:01','updated_at' => '2021-09-15 09:47:01'),
			  array('id' => '34','category_id' => '34','name' => 'Ryzen 8','lang' => 'en','created_at' => '2021-09-15 09:47:22','updated_at' => '2021-09-15 09:47:22'),
			  array('id' => '35','category_id' => '35','name' => 'Ryzen 9','lang' => 'en','created_at' => '2021-09-15 09:50:19','updated_at' => '2021-09-15 09:50:19'),
			  array('id' => '36','category_id' => '36','name' => 'K8 series','lang' => 'en','created_at' => '2021-09-15 09:51:11','updated_at' => '2021-09-15 09:51:11'),
			  array('id' => '37','category_id' => '37','name' => 'K10 series APUs','lang' => 'en','created_at' => '2021-09-15 09:51:51','updated_at' => '2021-09-15 09:51:51'),
			  array('id' => '38','category_id' => '38','name' => 'K10 series CPUs','lang' => 'en','created_at' => '2021-09-15 09:52:44','updated_at' => '2021-09-15 09:52:44'),
			  array('id' => '39','category_id' => '39','name' => 'Zen 2 series','lang' => 'en','created_at' => '2021-09-15 09:53:28','updated_at' => '2021-09-15 09:53:28'),
			  array('id' => '40','category_id' => '40','name' => 'Zen 3 series','lang' => 'en','created_at' => '2021-09-15 09:53:54','updated_at' => '2021-09-15 09:53:54'),
			  array('id' => '41','category_id' => '41','name' => 'Accessories & Supplies','lang' => 'en','created_at' => '2021-09-15 09:58:27','updated_at' => '2021-09-15 09:58:27'),
			  array('id' => '42','category_id' => '42','name' => 'Camera & Photo','lang' => 'en','created_at' => '2021-09-15 09:59:13','updated_at' => '2021-09-15 09:59:13'),
			  array('id' => '43','category_id' => '43','name' => 'Car & Electronic vehicles','lang' => 'en','created_at' => '2021-09-15 09:59:44','updated_at' => '2021-09-15 09:59:44'),
			  array('id' => '44','category_id' => '44','name' => 'Home Audio','lang' => 'en','created_at' => '2021-09-15 10:00:13','updated_at' => '2021-09-15 10:00:13'),
			  array('id' => '45','category_id' => '45','name' => 'Office Electronics','lang' => 'en','created_at' => '2021-09-15 10:00:34','updated_at' => '2021-09-15 10:00:34'),
			  array('id' => '46','category_id' => '46','name' => 'Portable Audio & Video','lang' => 'en','created_at' => '2021-09-15 10:01:19','updated_at' => '2021-09-15 10:01:19'),
			  array('id' => '47','category_id' => '47','name' => 'Television & Video','lang' => 'en','created_at' => '2021-09-15 10:01:46','updated_at' => '2021-09-15 10:01:46'),
			  array('id' => '48','category_id' => '48','name' => 'Video Projectors','lang' => 'en','created_at' => '2021-09-15 10:02:17','updated_at' => '2021-09-15 10:02:17'),
			  array('id' => '49','category_id' => '49','name' => 'Computers','lang' => 'en','created_at' => '2021-09-15 10:03:20','updated_at' => '2021-09-15 10:03:20'),
			  array('id' => '50','category_id' => '50','name' => 'Computer Components','lang' => 'en','created_at' => '2021-09-15 10:04:59','updated_at' => '2021-09-15 10:04:59'),
			  array('id' => '51','category_id' => '51','name' => 'Computer & Tablets','lang' => 'en','created_at' => '2021-09-15 10:10:37','updated_at' => '2021-09-15 10:10:37'),
			  array('id' => '52','category_id' => '52','name' => 'External Component','lang' => 'en','created_at' => '2021-09-15 10:11:09','updated_at' => '2021-09-15 10:11:09'),
			  array('id' => '53','category_id' => '53','name' => 'Monitors','lang' => 'en','created_at' => '2021-09-15 10:11:24','updated_at' => '2021-09-15 10:11:24'),
			  array('id' => '54','category_id' => '54','name' => 'Printer','lang' => 'en','created_at' => '2021-09-15 10:11:51','updated_at' => '2021-09-15 10:11:51'),
			  array('id' => '55','category_id' => '55','name' => 'Scanners','lang' => 'en','created_at' => '2021-09-15 10:12:16','updated_at' => '2021-09-15 10:12:16'),
			  array('id' => '56','category_id' => '56','name' => 'Tablet','lang' => 'en','created_at' => '2021-09-15 10:20:04','updated_at' => '2021-09-15 10:20:04'),
			  array('id' => '57','category_id' => '57','name' => 'Amazon Fire 3','lang' => 'en','created_at' => '2021-09-15 10:21:45','updated_at' => '2021-09-15 10:21:45'),
			  array('id' => '58','category_id' => '58','name' => 'Amazon Kindle','lang' => 'en','created_at' => '2021-09-15 10:22:06','updated_at' => '2021-09-15 10:22:06'),
			  array('id' => '59','category_id' => '59','name' => 'Home & Kitchen','lang' => 'en','created_at' => '2021-09-15 10:23:28','updated_at' => '2021-09-15 10:23:28'),
			  array('id' => '60','category_id' => '60','name' => 'Kitchen & Dinning','lang' => 'en','created_at' => '2021-09-15 10:24:03','updated_at' => '2021-09-15 10:24:03'),
			  array('id' => '61','category_id' => '61','name' => 'Wall Art','lang' => 'en','created_at' => '2021-09-15 10:24:28','updated_at' => '2021-09-15 10:24:28'),
			  array('id' => '62','category_id' => '62','name' => 'Irons and Steamers','lang' => 'en','created_at' => '2021-09-15 10:24:52','updated_at' => '2021-09-15 10:24:52'),
			  array('id' => '63','category_id' => '63','name' => 'Lighting & Ceiling Fans','lang' => 'en','created_at' => '2021-09-15 10:25:43','updated_at' => '2021-09-15 10:25:43'),
			  array('id' => '64','category_id' => '64','name' => 'Bedding','lang' => 'en','created_at' => '2021-09-15 10:26:20','updated_at' => '2021-09-15 10:26:20'),
			  array('id' => '65','category_id' => '65','name' => 'Boy\'s Fashion','lang' => 'en','created_at' => '2021-09-15 10:27:20','updated_at' => '2021-09-15 10:27:40'),
			  array('id' => '66','category_id' => '66','name' => 'T-shirt','lang' => 'en','created_at' => '2021-09-15 10:28:20','updated_at' => '2021-09-15 10:28:20'),
			  array('id' => '67','category_id' => '67','name' => 'Trousers','lang' => 'en','created_at' => '2021-09-15 10:28:35','updated_at' => '2021-09-15 10:28:35'),
			  array('id' => '68','category_id' => '68','name' => 'Skirt','lang' => 'en','created_at' => '2021-09-15 10:29:21','updated_at' => '2021-09-15 10:29:21'),
			  array('id' => '69','category_id' => '69','name' => 'Silk Sarees','lang' => 'en','created_at' => '2021-09-15 10:30:08','updated_at' => '2021-09-15 10:30:08'),
			  array('id' => '70','category_id' => '70','name' => 'Bhatiya','lang' => 'en','created_at' => '2021-09-15 10:30:24','updated_at' => '2021-09-15 10:30:24'),
			  array('id' => '71','category_id' => '71','name' => 'Sun glass','lang' => 'en','created_at' => '2021-09-15 10:30:53','updated_at' => '2021-09-15 10:30:53'),
			  array('id' => '72','category_id' => '72','name' => 'Round Neck','lang' => 'en','created_at' => '2021-09-15 10:31:15','updated_at' => '2021-09-15 10:31:15'),
			  array('id' => '73','category_id' => '73','name' => 'Printed T-shirt','lang' => 'en','created_at' => '2021-09-15 10:31:41','updated_at' => '2021-09-15 10:31:41'),
			  array('id' => '74','category_id' => '74','name' => 'Collar Neck','lang' => 'en','created_at' => '2021-09-15 10:32:19','updated_at' => '2021-09-15 10:32:19'),
			  array('id' => '75','category_id' => '75','name' => 'Both Shirt and Pants','lang' => 'en','created_at' => '2021-09-15 10:33:26','updated_at' => '2021-09-15 10:33:26'),
			  array('id' => '76','category_id' => '76','name' => 'Back Case','lang' => 'en','created_at' => '2021-09-15 10:37:29','updated_at' => '2021-09-15 10:37:29'),
			  array('id' => '77','category_id' => '77','name' => 'Tempered Glass','lang' => 'en','created_at' => '2021-09-15 10:37:48','updated_at' => '2021-09-15 10:37:48'),
			  array('id' => '78','category_id' => '78','name' => 'Printed Pouch','lang' => 'en','created_at' => '2021-09-15 10:38:14','updated_at' => '2021-09-15 10:38:14'),
			  array('id' => '79','category_id' => '79','name' => 'USB Cable','lang' => 'en','created_at' => '2021-09-15 10:39:16','updated_at' => '2021-09-15 10:39:16'),
			  array('id' => '80','category_id' => '80','name' => 'Smart  TV','lang' => 'en','created_at' => '2021-09-15 11:05:32','updated_at' => '2021-09-15 11:05:32'),
			  array('id' => '81','category_id' => '81','name' => 'UHD TV','lang' => 'en','created_at' => '2021-09-15 11:05:52','updated_at' => '2021-09-15 11:05:52'),
			  array('id' => '82','category_id' => '82','name' => 'OLED TV','lang' => 'en','created_at' => '2021-09-15 11:06:39','updated_at' => '2021-09-15 11:06:39'),
			  array('id' => '83','category_id' => '83','name' => 'QLED TV','lang' => 'en','created_at' => '2021-09-15 11:09:03','updated_at' => '2021-09-15 11:09:03')
			),
        );
    }
}
