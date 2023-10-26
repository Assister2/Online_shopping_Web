<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('addresses')->delete();

        \DB::table('addresses')->insert(
        	array(
			  	
			  	array('id' => '1','user_id' => '9','address' => 'Sanfracisco','country' => 'United States','city' => 'California','longitude' => NULL,'latitude' => NULL,'postal_code' => '10017','phone' => '984124575','set_default' => '0','created_at' => '2021-09-22 07:06:18','updated_at' => '2021-09-22 07:06:18'),
  				
  				array('id' => '2','user_id' => '16','address' => '123','country' => 'United States','city' => 'California','longitude' => NULL,'latitude' => NULL,'postal_code' => '65230','phone' => '9519519510','set_default' => '0','created_at' => '2021-09-27 17:20:57','updated_at' => '2021-09-27 17:20:57'),
  				
  				array('id' => '3','user_id' => '17','address' => 'M185 , malligai nagar, madurai','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '625017','phone' => '654654654','set_default' => '0','created_at' => '2021-09-28 01:58:09','updated_at' => '2021-09-28 01:58:09'),
  				
  				array('id' => '4','user_id' => '18','address' => 'Anna nagar
				near to alagappa temple','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '644228','phone' => '09865321245','set_default' => '0','created_at' => '2021-09-28 21:03:11','updated_at' => '2021-09-28 21:03:11'),
  				
  				array('id' => '5','user_id' => '21','address' => 'Kathir','country' => 'United States','city' => 'California','longitude' => NULL,'latitude' => NULL,'postal_code' => '789455','phone' => '7788414745454','set_default' => '0','created_at' => '2021-10-02 04:55:50','updated_at' => '2021-10-02 04:55:50'),
  				
  				array('id' => '6','user_id' => '23','address' => 'sqlcklqskqlskc','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '15222','phone' => '2555555','set_default' => '0','created_at' => '2021-10-07 13:13:28','updated_at' => '2021-10-07 13:13:28'),
  				
  				array('id' => '7','user_id' => '29','address' => 'Cite sonelgaz','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '16000','phone' => '123455555','set_default' => '0','created_at' => '2021-10-09 06:47:36','updated_at' => '2021-10-09 06:47:36'),
  				
  				array('id' => '8','user_id' => '34','address' => 'No 5/57, Ambal Nagar, Mangadu, Chennai 600128','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '600128','phone' => '+91987 6543210','set_default' => '0','created_at' => '2021-10-12 01:58:11','updated_at' => '2021-10-12 01:58:11'),
  				
  				array('id' => '9','user_id' => '38','address' => '123, Kumar street','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '625026','phone' => '8452136425','set_default' => '0','created_at' => '2021-10-18 17:10:57','updated_at' => '2021-10-18 17:10:57'),
  				
  				array('id' => '10','user_id' => '39','address' => '1,2,
				Madurai','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '10017','phone' => '984124575','set_default' => '0','created_at' => '2021-10-18 21:06:50','updated_at' => '2021-10-18 21:06:50'),
  				
  				array('id' => '11','user_id' => '39','address' => '1,2,
				Madurai','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '10017','phone' => '984124575','set_default' => '0','created_at' => '2021-10-18 21:06:50','updated_at' => '2021-10-18 21:06:50'),
  			
  				array('id' => '12','user_id' => '33','address' => 'efdas','country' => 'Albania','city' => 'California','longitude' => NULL,'latitude' => NULL,'postal_code' => 'fwefewfwfe','phone' => 'wefwefe','set_default' => '0','created_at' => '2021-10-26 03:20:35','updated_at' => '2021-11-14 13:57:15'),
  				
  				array('id' => '13','user_id' => '27','address' => '1,2,
				Madurai','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '10017','phone' => '984124575','set_default' => '0','created_at' => '2021-11-02 16:18:16','updated_at' => '2021-11-02 16:18:16'),
  				
  				array('id' => '14','user_id' => '44','address' => 'Madurai','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '625014','phone' => '+919585874412','set_default' => '0','created_at' => '2021-11-09 17:48:35','updated_at' => '2021-11-09 17:48:35'),
  				
  				array('id' => '15','user_id' => '45','address' => 'CS/5, Sidco Industrial Estate Kappalur, State Bank Road, Madurai - 625002','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '625002','phone' => '04523099680','set_default' => '0','created_at' => '2021-11-09 18:36:54','updated_at' => '2021-11-09 18:36:54'),
  				
  				array('id' => '16','user_id' => '48','address' => 'nehrul street','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '625001','phone' => '9600812208','set_default' => '0','created_at' => '2021-11-10 16:37:39','updated_at' => '2021-11-10 16:37:39'),
  				
  				array('id' => '17','user_id' => '48','address' => 'raju street','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '625501','phone' => '9600812020','set_default' => '0','created_at' => '2021-11-10 16:38:27','updated_at' => '2021-11-10 16:38:27'),
  				
  				array('id' => '18','user_id' => '52','address' => 'M185 , malligai nagar, madurai','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '625017','phone' => '65465654','set_default' => '0','created_at' => '2021-11-17 15:42:36','updated_at' => '2021-11-17 15:42:36'),
  				
  				array('id' => '19','user_id' => '53','address' => 'No 12/9, Santhosh Raj Plaza,3rd Floor, Subburaman Street, Gandhi Nagar, Madurai, Tamil Nadu 625020','country' => 'India','city' => 'Madurai','longitude' => NULL,'latitude' => NULL,'postal_code' => '625020','phone' => '+918248697700','set_default' => '0','created_at' => '2021-11-18 22:02:22','updated_at' => '2021-11-18 22:02:22')
			  	
			),
        );
    }
}
