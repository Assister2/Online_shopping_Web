<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductStockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('product_stocks')->delete();

        \DB::table('product_stocks')->insert(
        	array(
			  array('id' => '1','product_id' => '1','variant' => '','sku' => NULL,'price' => '450.00','qty' => '30','image' => NULL,'created_at' => '2021-10-01 21:18:02','updated_at' => '2021-10-01 21:18:02'),
			  array('id' => '3','product_id' => '3','variant' => '','sku' => NULL,'price' => '500.00','qty' => '50','image' => NULL,'created_at' => '2021-10-01 22:50:53','updated_at' => '2021-10-01 22:50:53'),
			  array('id' => '4','product_id' => '4','variant' => '','sku' => NULL,'price' => '150.00','qty' => '30','image' => NULL,'created_at' => '2021-10-01 22:56:15','updated_at' => '2021-10-01 22:56:15'),
			  array('id' => '5','product_id' => '5','variant' => '','sku' => NULL,'price' => '250.00','qty' => '50','image' => NULL,'created_at' => '2021-10-01 22:58:09','updated_at' => '2021-10-01 22:58:09'),
			  array('id' => '6','product_id' => '6','variant' => '','sku' => NULL,'price' => '780.00','qty' => '48','image' => NULL,'created_at' => '2021-10-01 23:00:35','updated_at' => '2021-10-11 23:48:21'),
			  array('id' => '7','product_id' => '7','variant' => '','sku' => NULL,'price' => '450.00','qty' => '48','image' => NULL,'created_at' => '2021-10-01 23:02:12','updated_at' => '2021-10-07 13:14:19'),
			  array('id' => '10','product_id' => '8','variant' => '','sku' => NULL,'price' => '1000.00','qty' => '11','image' => NULL,'created_at' => '2021-10-01 23:16:30','updated_at' => '2021-10-07 13:14:19'),
			  array('id' => '11','product_id' => '9','variant' => '','sku' => NULL,'price' => '750.00','qty' => '15','image' => NULL,'created_at' => '2021-10-01 23:17:49','updated_at' => '2021-10-01 23:17:49'),
			  array('id' => '12','product_id' => '2','variant' => '','sku' => NULL,'price' => '560.00','qty' => '50','image' => NULL,'created_at' => '2021-10-01 23:18:45','updated_at' => '2021-10-01 23:18:45'),
			  array('id' => '13','product_id' => '10','variant' => '','sku' => NULL,'price' => '150.00','qty' => '100','image' => NULL,'created_at' => '2021-10-01 23:21:02','updated_at' => '2021-10-01 23:21:02'),
			  array('id' => '16','product_id' => '13','variant' => '','sku' => NULL,'price' => '250.00','qty' => '50','image' => NULL,'created_at' => '2021-10-01 23:27:20','updated_at' => '2021-10-01 23:27:20'),
			  array('id' => '17','product_id' => '14','variant' => '','sku' => NULL,'price' => '150.00','qty' => '50','image' => NULL,'created_at' => '2021-10-01 23:29:13','updated_at' => '2021-10-01 23:29:13'),
			  array('id' => '18','product_id' => '15','variant' => '','sku' => NULL,'price' => '150.00','qty' => '148','image' => NULL,'created_at' => '2021-10-01 23:34:18','updated_at' => '2021-11-18 22:03:01'),
			  array('id' => '19','product_id' => '11','variant' => '','sku' => NULL,'price' => '150.00','qty' => '19','image' => NULL,'created_at' => '2021-10-01 23:34:40','updated_at' => '2021-10-26 03:20:48'),
			  array('id' => '20','product_id' => '16','variant' => '','sku' => NULL,'price' => '150.00','qty' => '50','image' => NULL,'created_at' => '2021-10-01 23:36:55','updated_at' => '2021-10-01 23:36:55'),
			  array('id' => '22','product_id' => '12','variant' => '','sku' => NULL,'price' => '250.00','qty' => '49','image' => NULL,'created_at' => '2021-10-01 23:37:54','updated_at' => '2021-10-18 21:07:44'),
			  array('id' => '23','product_id' => '17','variant' => '','sku' => NULL,'price' => '150.00','qty' => '148','image' => NULL,'created_at' => '2021-10-01 23:39:29','updated_at' => '2021-10-27 04:50:23'),
			  array('id' => '24','product_id' => '18','variant' => '','sku' => NULL,'price' => '150.00','qty' => '150','image' => NULL,'created_at' => '2021-10-01 23:41:31','updated_at' => '2021-10-01 23:41:31'),
			  array('id' => '26','product_id' => '20','variant' => '','sku' => NULL,'price' => '80.00','qty' => '78','image' => NULL,'created_at' => '2021-10-01 23:45:22','updated_at' => '2021-10-01 23:45:22'),
			  array('id' => '27','product_id' => '19','variant' => '','sku' => NULL,'price' => '150.00','qty' => '100','image' => NULL,'created_at' => '2021-10-01 23:45:45','updated_at' => '2021-10-01 23:45:45'),
			  array('id' => '29','product_id' => '22','variant' => '','sku' => NULL,'price' => '250.00','qty' => '150','image' => NULL,'created_at' => '2021-10-01 23:59:29','updated_at' => '2021-10-01 23:59:29'),
			  array('id' => '30','product_id' => '23','variant' => '','sku' => NULL,'price' => '150.00','qty' => '100','image' => NULL,'created_at' => '2021-10-02 00:01:09','updated_at' => '2021-10-02 00:01:09'),
			  array('id' => '32','product_id' => '24','variant' => '','sku' => NULL,'price' => '150.00','qty' => '14','image' => NULL,'created_at' => '2021-10-02 00:02:56','updated_at' => '2021-10-18 17:20:11'),
			  array('id' => '34','product_id' => '25','variant' => '','sku' => NULL,'price' => '100.00','qty' => '100','image' => NULL,'created_at' => '2021-10-02 00:38:35','updated_at' => '2021-10-02 00:38:35'),
			  array('id' => '35','product_id' => '26','variant' => '','sku' => NULL,'price' => '150.00','qty' => '60','image' => NULL,'created_at' => '2021-10-02 00:42:29','updated_at' => '2021-10-02 00:42:29'),
			  array('id' => '36','product_id' => '27','variant' => '','sku' => NULL,'price' => '75.00','qty' => '11','image' => NULL,'created_at' => '2021-10-02 00:44:38','updated_at' => '2021-11-17 15:57:24'),
			  array('id' => '38','product_id' => '28','variant' => '','sku' => NULL,'price' => '120000.00','qty' => '0','image' => NULL,'created_at' => '2021-10-07 23:43:38','updated_at' => '2021-10-07 23:43:38'),
			  array('id' => '40','product_id' => '29','variant' => '','sku' => NULL,'price' => '2330.00','qty' => '0','image' => NULL,'created_at' => '2021-10-12 23:12:13','updated_at' => '2021-10-12 23:12:13'),
			  array('id' => '41','product_id' => '30','variant' => '','sku' => NULL,'price' => '75.00','qty' => '10','image' => NULL,'created_at' => '2021-10-24 17:39:05','updated_at' => '2021-11-10 16:40:19'),
			  array('id' => '42','product_id' => '31','variant' => '','sku' => NULL,'price' => '440.00','qty' => '28','image' => NULL,'created_at' => '2021-10-24 23:39:59','updated_at' => '2021-10-24 23:45:16'),
			  array('id' => '47','product_id' => '32','variant' => '','sku' => 'sku','price' => '15999.00','qty' => '','image' => NULL,'created_at' => '2021-11-09 18:42:04','updated_at' => '2021-11-17 15:43:40'),
			  array('id' => '48','product_id' => '32','variant' => '','sku' => 'sku','price' => '16999.00','qty' => '','image' => NULL,'created_at' => '2021-11-09 18:42:04','updated_at' => '2021-11-09 18:42:04'),
			  array('id' => '49','product_id' => '21','variant' => '','sku' => NULL,'price' => '150.00','qty' => '24','image' => NULL,'created_at' => '2021-11-20 16:43:57','updated_at' => '2021-11-20 16:43:57')
			),
        );
    }
}
