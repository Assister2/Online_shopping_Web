<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('currencies')->delete();

        \DB::table('currencies')->insert(
        	array(
			  array('id' => '1','name' => 'U.S. Dollar','symbol' => '$','exchange_rate' => '1.00000','status' => '1','code' => 'USD','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-17 11:20:52'),
			  array('id' => '2','name' => 'Australian Dollar','symbol' => '$','exchange_rate' => '1.28000','status' => '1','code' => 'AUD','created_at' => '2018-10-09 17:05:08','updated_at' => '2019-02-04 11:21:55'),
			  array('id' => '5','name' => 'Brazilian Real','symbol' => 'R$','exchange_rate' => '3.25000','status' => '1','code' => 'BRL','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-17 11:21:00'),
			  array('id' => '6','name' => 'Canadian Dollar','symbol' => '$','exchange_rate' => '1.27000','status' => '1','code' => 'CAD','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '7','name' => 'Czech Koruna','symbol' => 'Kč','exchange_rate' => '20.65000','status' => '1','code' => 'CZK','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '8','name' => 'Danish Krone','symbol' => 'kr','exchange_rate' => '6.05000','status' => '1','code' => 'DKK','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '9','name' => 'Euro','symbol' => '€','exchange_rate' => '0.85000','status' => '1','code' => 'EUR','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '10','name' => 'Hong Kong Dollar','symbol' => '$','exchange_rate' => '7.83000','status' => '1','code' => 'HKD','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '11','name' => 'Hungarian Forint','symbol' => 'Ft','exchange_rate' => '255.24000','status' => '1','code' => 'HUF','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '12','name' => 'Israeli New Sheqel','symbol' => '₪','exchange_rate' => '3.48000','status' => '1','code' => 'ILS','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '13','name' => 'Japanese Yen','symbol' => '¥','exchange_rate' => '107.12000','status' => '1','code' => 'JPY','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '14','name' => 'Malaysian Ringgit','symbol' => 'RM','exchange_rate' => '3.91000','status' => '1','code' => 'MYR','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '15','name' => 'Mexican Peso','symbol' => '$','exchange_rate' => '18.72000','status' => '1','code' => 'MXN','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '16','name' => 'Norwegian Krone','symbol' => 'kr','exchange_rate' => '7.83000','status' => '1','code' => 'NOK','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '17','name' => 'New Zealand Dollar','symbol' => '$','exchange_rate' => '1.38000','status' => '1','code' => 'NZD','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '18','name' => 'Philippine Peso','symbol' => '₱','exchange_rate' => '52.26000','status' => '1','code' => 'PHP','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '19','name' => 'Polish Zloty','symbol' => 'zł','exchange_rate' => '3.39000','status' => '1','code' => 'PLN','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '20','name' => 'Pound Sterling','symbol' => '£','exchange_rate' => '0.72000','status' => '1','code' => 'GBP','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '21','name' => 'Russian Ruble','symbol' => 'руб','exchange_rate' => '55.93000','status' => '1','code' => 'RUB','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '22','name' => 'Singapore Dollar','symbol' => '$','exchange_rate' => '1.32000','status' => '1','code' => 'SGD','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '23','name' => 'Swedish Krona','symbol' => 'kr','exchange_rate' => '8.19000','status' => '1','code' => 'SEK','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '24','name' => 'Swiss Franc','symbol' => 'CHF','exchange_rate' => '0.94000','status' => '1','code' => 'CHF','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '26','name' => 'Thai Baht','symbol' => '฿','exchange_rate' => '31.39000','status' => '1','code' => 'THB','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-10-09 17:05:08'),
			  array('id' => '27','name' => 'Taka','symbol' => '৳','exchange_rate' => '84.00000','status' => '1','code' => 'BDT','created_at' => '2018-10-09 17:05:08','updated_at' => '2018-12-02 10:46:13'),
			  array('id' => '28','name' => 'Indian Rupee','symbol' => 'Rs','exchange_rate' => '68.45000','status' => '1','code' => 'INR','created_at' => '2019-07-07 16:03:46','updated_at' => '2021-11-03 13:33:53'),
			  array('id' => '29','name' => 'India','symbol' => '$','exchange_rate' => '68.45000','status' => '1','code' => 'Rupee','created_at' => '2021-11-02 16:48:11','updated_at' => '2021-11-12 20:26:09')
			),
        );
    }
}
