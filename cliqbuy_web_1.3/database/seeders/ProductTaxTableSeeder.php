<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class ProductTaxTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('product_taxes')->delete();

        \DB::table('product_taxes')->insert(
        	array(
			  array('id' => '1','product_id' => '1','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 21:18:02','updated_at' => '2021-10-01 21:18:02'),
			  array('id' => '3','product_id' => '3','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 22:50:53','updated_at' => '2021-10-01 22:50:53'),
			  array('id' => '4','product_id' => '4','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 22:56:15','updated_at' => '2021-10-01 22:56:15'),
			  array('id' => '5','product_id' => '5','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 22:58:09','updated_at' => '2021-10-01 22:58:09'),
			  array('id' => '6','product_id' => '6','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:00:35','updated_at' => '2021-10-01 23:00:35'),
			  array('id' => '7','product_id' => '7','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:02:12','updated_at' => '2021-10-01 23:02:12'),
			  array('id' => '10','product_id' => '8','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:16:30','updated_at' => '2021-10-01 23:16:30'),
			  array('id' => '11','product_id' => '9','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:17:49','updated_at' => '2021-10-01 23:17:49'),
			  array('id' => '12','product_id' => '2','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:18:45','updated_at' => '2021-10-01 23:18:45'),
			  array('id' => '13','product_id' => '10','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:21:02','updated_at' => '2021-10-01 23:21:02'),
			  array('id' => '16','product_id' => '13','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:27:20','updated_at' => '2021-10-01 23:27:20'),
			  array('id' => '17','product_id' => '14','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:29:13','updated_at' => '2021-10-01 23:29:13'),
			  array('id' => '18','product_id' => '15','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:34:18','updated_at' => '2021-10-01 23:34:18'),
			  array('id' => '19','product_id' => '11','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:34:40','updated_at' => '2021-10-01 23:34:40'),
			  array('id' => '20','product_id' => '16','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:36:55','updated_at' => '2021-10-01 23:36:55'),
			  array('id' => '22','product_id' => '12','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:37:54','updated_at' => '2021-10-01 23:37:54'),
			  array('id' => '23','product_id' => '17','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:39:29','updated_at' => '2021-10-01 23:39:29'),
			  array('id' => '24','product_id' => '18','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:41:31','updated_at' => '2021-10-01 23:41:31'),
			  array('id' => '26','product_id' => '20','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:45:22','updated_at' => '2021-10-01 23:45:22'),
			  array('id' => '27','product_id' => '19','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:45:45','updated_at' => '2021-10-01 23:45:45'),
			  array('id' => '29','product_id' => '22','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-01 23:59:29','updated_at' => '2021-10-01 23:59:29'),
			  array('id' => '30','product_id' => '23','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-02 00:01:09','updated_at' => '2021-10-02 00:01:09'),
			  array('id' => '32','product_id' => '24','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-02 00:02:56','updated_at' => '2021-10-02 00:02:56'),
			  array('id' => '34','product_id' => '25','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-02 00:38:35','updated_at' => '2021-10-02 00:38:35'),
			  array('id' => '35','product_id' => '26','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-02 00:42:29','updated_at' => '2021-10-02 00:42:29'),
			  array('id' => '36','product_id' => '27','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-02 00:44:38','updated_at' => '2021-10-02 00:44:38'),
			  array('id' => '38','product_id' => '28','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-07 23:43:38','updated_at' => '2021-10-07 23:43:38'),
			  array('id' => '40','product_id' => '29','tax_id' => '3','tax' => '10.00','tax_type' => 'percent','created_at' => '2021-10-12 23:12:13','updated_at' => '2021-10-12 23:12:13'),
			  array('id' => '41','product_id' => '31','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-10-24 23:39:59','updated_at' => '2021-10-24 23:39:59'),
			  array('id' => '44','product_id' => '32','tax_id' => '3','tax' => '5.00','tax_type' => 'amount','created_at' => '2021-11-09 18:42:04','updated_at' => '2021-11-09 18:42:04'),
			  array('id' => '45','product_id' => '21','tax_id' => '3','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-11-20 16:43:57','updated_at' => '2021-11-20 16:43:57'),
			  array('id' => '46','product_id' => '21','tax_id' => '4','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-11-20 16:43:57','updated_at' => '2021-11-20 16:43:57'),
			  array('id' => '47','product_id' => '21','tax_id' => '5','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-11-20 16:43:57','updated_at' => '2021-11-20 16:43:57'),
			  array('id' => '48','product_id' => '21','tax_id' => '6','tax' => '0.00','tax_type' => 'amount','created_at' => '2021-11-20 16:43:57','updated_at' => '2021-11-20 16:43:57')
			),
        );
    }
}
