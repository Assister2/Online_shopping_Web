<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('shops')->delete();

        \DB::table('shops')->insert(
        	array(
			  array('id' => '1','user_id' => '3','name' => 'Demo Seller Shop','logo' => '111','sliders' => NULL,'phone' => '885495490','address' => 'House : Demo, Road : Demo, Section : Demo','facebook' => 'www.facebook.com','google' => 'www.google.com','twitter' => 'www.twitter.com','youtube' => 'www.youtube.com','slug' => 'Demo-Seller-Shop-1','meta_title' => 'Demo Seller Shop Title','meta_description' => 'Demo description','pick_up_point_id' => '[]','shipping_cost' => '0.00','created_at' => '2018-11-27 15:53:13','updated_at' => '2021-10-02 04:53:24'),
			  array('id' => '2','user_id' => '9','name' => 'All Products','logo' => '106','sliders' => NULL,'phone' => '78458447','address' => 'madurai','facebook' => '#','google' => '#','twitter' => '#','youtube' => '#','slug' => 'All-Products-2','meta_title' => 'Shop open','meta_description' => 'Meta description','pick_up_point_id' => '[]','shipping_cost' => '0.00','created_at' => '2021-09-14 06:02:58','updated_at' => '2021-10-02 00:46:59'),
			  array('id' => '7','user_id' => '18','name' => 'suji\'s store','logo' => '110','sliders' => NULL,'phone' => '9865321245','address' => 'anna nagar, madurai','facebook' => NULL,'google' => NULL,'twitter' => NULL,'youtube' => NULL,'slug' => 'suji\'s-store-7','meta_title' => 'suji','meta_description' => 'store','pick_up_point_id' => '[]','shipping_cost' => '0.00','created_at' => '2021-09-28 17:12:53','updated_at' => '2021-10-02 04:44:46'),
			  array('id' => '8','user_id' => '22','name' => 'Vinoth Store','logo' => '63','sliders' => NULL,'phone' => NULL,'address' => NULL,'facebook' => NULL,'google' => NULL,'twitter' => NULL,'youtube' => NULL,'slug' => 'demo-shop-22','meta_title' => NULL,'meta_description' => NULL,'pick_up_point_id' => NULL,'shipping_cost' => '0.00','created_at' => '2021-12-13 19:55:41','updated_at' => '2021-10-07 12:34:51'),
			  array('id' => '9','user_id' => '24','name' => 'Siva shop','logo' => '4','sliders' => NULL,'phone' => NULL,'address' => NULL,'facebook' => NULL,'google' => NULL,'twitter' => NULL,'youtube' => NULL,'slug' => 'demo-shop-24','meta_title' => NULL,'meta_description' => NULL,'pick_up_point_id' => NULL,'shipping_cost' => '0.00','created_at' => '2021-12-13 19:57:11','updated_at' => '2021-10-07 23:20:52'),
			  array('id' => '10','user_id' => '25','name' => 'Madurai Outlet','logo' => '97','sliders' => NULL,'phone' => NULL,'address' => 'Madurai, Tamilnadu, India','facebook' => NULL,'google' => NULL,'twitter' => NULL,'youtube' => NULL,'slug' => 'Madurai-Outlet-','meta_title' => NULL,'meta_description' => NULL,'pick_up_point_id' => NULL,'shipping_cost' => '0.00','created_at' => '2021-12-13 19:58:00','updated_at' => '2021-10-08 01:18:07'),
			  array('id' => '11','user_id' => '26','name' => 'Kumar store','logo' => '34','sliders' => NULL,'phone' => NULL,'address' => NULL,'facebook' => NULL,'google' => NULL,'twitter' => NULL,'youtube' => NULL,'slug' => 'demo-shop-26','meta_title' => NULL,'meta_description' => NULL,'pick_up_point_id' => NULL,'shipping_cost' => '0.00','created_at' => '2021-12-13 19:53:42','updated_at' => '2021-10-08 07:16:47'),
			  array('id' => '13','user_id' => '29','name' => 'Raj Shop','logo' => '89','sliders' => NULL,'phone' => NULL,'address' => NULL,'facebook' => NULL,'google' => NULL,'twitter' => NULL,'youtube' => NULL,'slug' => 'demo-shop-29','meta_title' => NULL,'meta_description' => NULL,'pick_up_point_id' => NULL,'shipping_cost' => '0.00','created_at' => '2021-12-13 19:58:26','updated_at' => '2021-10-09 06:36:19'),
			  array('id' => '14','user_id' => '30','name' => 'parashop','logo' => '91','sliders' => NULL,'phone' => NULL,'address' => 'alger algerie','facebook' => NULL,'google' => NULL,'twitter' => NULL,'youtube' => NULL,'slug' => 'parashop-','meta_title' => NULL,'meta_description' => NULL,'pick_up_point_id' => NULL,'shipping_cost' => '0.00','created_at' => '2021-12-13 19:53:14','updated_at' => '2021-10-09 07:50:25'),
			  array('id' => '15','user_id' => '31','name' => 'Alagar Shop','logo' => '76','sliders' => NULL,'phone' => NULL,'address' => NULL,'facebook' => NULL,'google' => NULL,'twitter' => NULL,'youtube' => NULL,'slug' => 'demo-shop-31','meta_title' => NULL,'meta_description' => NULL,'pick_up_point_id' => NULL,'shipping_cost' => '0.00','created_at' => '2021-12-13 20:01:12','updated_at' => '2021-10-09 08:01:30'),
			  array('id' => '16','user_id' => '36','name' => 'Santosh Raj Plaza','logo' => '56','sliders' => NULL,'phone' => '+918111002099','address' => '12/9,SANTHOSH RAJ PLAZA,3 RD FLOOR','facebook' => NULL,'google' => NULL,'twitter' => NULL,'youtube' => NULL,'slug' => 'Santosh-Raj-Plaza-16','meta_title' => 'Ammazon Clone','meta_description' => 'Amazing products','pick_up_point_id' => '[]','shipping_cost' => '0.00','created_at' => '2021-12-13 19:52:35','updated_at' => '2021-10-12 20:57:23'),
			  array('id' => '17','user_id' => '45','name' => 'Madurai Store','logo' => '120','sliders' => NULL,'phone' => NULL,'address' => NULL,'facebook' => NULL,'google' => NULL,'twitter' => NULL,'youtube' => NULL,'slug' => 'demo-shop-45','meta_title' => NULL,'meta_description' => NULL,'pick_up_point_id' => NULL,'shipping_cost' => '0.00','created_at' => '2021-12-13 18:28:43','updated_at' => '2021-11-09 18:09:46'),
			  array('id' => '18','user_id' => '49','name' => 'hasan','logo' => '77','sliders' => NULL,'phone' => NULL,'address' => 'madurai','facebook' => NULL,'google' => NULL,'twitter' => NULL,'youtube' => NULL,'slug' => 'hasan-','meta_title' => NULL,'meta_description' => NULL,'pick_up_point_id' => NULL,'shipping_cost' => '0.00','created_at' => '2021-12-13 19:52:06','updated_at' => '2021-11-10 16:45:27')
			),
        );
    }
}
