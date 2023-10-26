<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('sellers')->delete();

        \DB::table('sellers')->insert(
        	array(
		  		
			  array('id' => '1','user_id' => '3','verification_status' => '1','verification_info' => '[{"type":"text","label":"Name","value":"Mr. Seller"},{"type":"select","label":"Marital Status","value":"Married"},{"type":"multi_select","label":"Company","value":"[\\"Company\\"]"},{"type":"select","label":"Gender","value":"Male"},{"type":"file","label":"Image","value":"uploads\\/verification_form\\/CRWqFifcbKqibNzllBhEyUSkV6m1viknGXMEhtiW.png"}]','cash_on_delivery_status' => '1','admin_to_pay' => '78.40','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2018-10-07 10:12:57','updated_at' => '2020-01-26 09:51:11'),
			  array('id' => '2','user_id' => '9','verification_status' => '1','verification_info' => NULL,'cash_on_delivery_status' => '1','admin_to_pay' => '1.20','bank_name' => 'Bank of YUP','bank_acc_name' => 'Merchant','bank_acc_no' => '1100000000000','bank_routing_no' => '0','bank_payment_status' => '1','created_at' => '2021-09-14 06:02:58','updated_at' => '2021-10-24 18:04:49'),
			  array('id' => '7','user_id' => '18','verification_status' => '1','verification_info' => NULL,'cash_on_delivery_status' => '0','admin_to_pay' => '0.00','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2021-09-28 17:12:53','updated_at' => '2021-10-01 21:07:31'),
			  array('id' => '8','user_id' => '22','verification_status' => '0','verification_info' => NULL,'cash_on_delivery_status' => '0','admin_to_pay' => '0.00','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2021-10-07 12:34:51','updated_at' => '2021-10-07 12:34:51'),
			  array('id' => '9','user_id' => '24','verification_status' => '0','verification_info' => NULL,'cash_on_delivery_status' => '0','admin_to_pay' => '0.00','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2021-10-07 23:20:52','updated_at' => '2021-10-07 23:20:52'),
			  array('id' => '10','user_id' => '25','verification_status' => '0','verification_info' => NULL,'cash_on_delivery_status' => '0','admin_to_pay' => '0.00','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2021-10-08 01:18:07','updated_at' => '2021-10-08 01:18:07'),
			  array('id' => '11','user_id' => '26','verification_status' => '0','verification_info' => NULL,'cash_on_delivery_status' => '0','admin_to_pay' => '0.00','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2021-10-08 07:16:47','updated_at' => '2021-10-08 07:16:47'),
			  array('id' => '14','user_id' => '29','verification_status' => '0','verification_info' => NULL,'cash_on_delivery_status' => '0','admin_to_pay' => '9320.00','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2021-10-09 06:36:19','updated_at' => '2021-10-09 06:57:40'),
			  array('id' => '16','user_id' => '30','verification_status' => '0','verification_info' => NULL,'cash_on_delivery_status' => '0','admin_to_pay' => '0.00','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2021-10-09 07:50:25','updated_at' => '2021-10-09 07:50:25'),
			  array('id' => '17','user_id' => '31','verification_status' => '0','verification_info' => NULL,'cash_on_delivery_status' => '0','admin_to_pay' => '0.00','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2021-10-09 08:01:30','updated_at' => '2021-10-09 08:01:30'),
			  array('id' => '18','user_id' => '36','verification_status' => '0','verification_info' => NULL,'cash_on_delivery_status' => '0','admin_to_pay' => '0.00','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2021-10-12 20:56:26','updated_at' => '2021-10-12 20:56:26'),
			  array('id' => '22','user_id' => '45','verification_status' => '1','verification_info' => NULL,'cash_on_delivery_status' => '0','admin_to_pay' => '0.00','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2021-11-09 18:09:46','updated_at' => '2021-11-09 18:09:59'),
			  array('id' => '23','user_id' => '49','verification_status' => '0','verification_info' => NULL,'cash_on_delivery_status' => '0','admin_to_pay' => '0.00','bank_name' => NULL,'bank_acc_name' => NULL,'bank_acc_no' => NULL,'bank_routing_no' => NULL,'bank_payment_status' => '0','created_at' => '2021-11-10 16:45:27','updated_at' => '2021-11-10 16:45:27')

			),
        );
    }
}
