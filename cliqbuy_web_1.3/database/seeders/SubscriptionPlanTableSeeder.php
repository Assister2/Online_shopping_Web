<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('subscription_plan')->delete();

        \DB::table('subscription_plan')->insert(
        	array(
			  	array('id' => '2','name' => 'Free Plan','description' => 'Free Plan Description','tagline' => '#Free Plan','duration' => '7','no_of_product' => '12','custom_plan' => 'No','is_free' => 'Yes','price' => '0.00','currency' => 'USD','status' => 'Active'),
                array('id' => '3','name' => 'Premium','description' => '2 Products','tagline' => '#Premium','duration' => '8','no_of_product' => '2','custom_plan' => 'No','is_free' => 'No','price' => '20.00','currency' => 'USD','status' => 'Active'),
                array('id' => '13','name' => 'ADMIN PLAN','description' => 'Update 250Products','tagline' => '250','duration' => '0','no_of_product' => '0','custom_plan' => 'Yes','is_free' => 'No','price' => '0.00','currency' => 'USD','status' => 'Active'),
                array('id' => '14','name' => 'Premier','description' => '4 Products /Premier Plans','tagline' => 'Premier plans','duration' => '12','no_of_product' => '4','custom_plan' => 'No','is_free' => 'No','price' => '35.00','currency' => 'USD','status' => 'Active'),
                array('id' => '15','name' => 'Test','description' => 'sdg','tagline' => '35443','duration' => '2','no_of_product' => '10','custom_plan' => 'No','is_free' => 'Yes','price' => '0.00','currency' => 'USD','status' => 'Active'),
                array('id' => '16','name' => 'New plan','description' => '5products','tagline' => 'Enjoy ur services','duration' => '2','no_of_product' => '5','custom_plan' => 'No','is_free' => 'No','price' => '65.00','currency' => 'USD','status' => 'Active'),
                array('id' => '17','name' => 'KKIK','description' => 'Kfjdf','tagline' => 'jumbo','duration' => '2','no_of_product' => '40','custom_plan' => 'No','is_free' => 'No','price' => '250.00','currency' => 'USD','status' => 'Active'),
                array('id' => '19','name' => 'AAAIK','description' => 'AAA','tagline' => 'AAA','duration' => '2','no_of_product' => '20','custom_plan' => 'No','is_free' => 'No','price' => '140.00','currency' => 'USD','status' => 'Active'),
                array('id' => '22','name' => '3 INR Plan','description' => 'Desc','tagline' => 'Tagline Data','duration' => '1','no_of_product' => '3','custom_plan' => 'No','is_free' => 'No','price' => '5.00','currency' => 'INR','status' => 'Active'),
                array('id' => '23','name' => '70 Rupee Plan','description' => '70 Rupee Plan Desc','tagline' => '70Inr','duration' => '1','no_of_product' => '4','custom_plan' => 'No','is_free' => 'No','price' => '40.00','currency' => 'Inr','status' => 'Active'),
                array('id' => '24','name' => '40 Ruppe Plan','description' => 'Test','tagline' => 'test','duration' => '1','no_of_product' => '4','custom_plan' => 'No','is_free' => 'No','price' => '40.00','currency' => 'INR','status' => 'Active'),
                array('id' => '25','name' => 'Ruppe Plan 40 Rs','description' => 'Ruppe Plan 40 Rs','tagline' => 'Test','duration' => '1','no_of_product' => '3','custom_plan' => 'No','is_free' => 'No','price' => '40.00','currency' => 'Rupee','status' => 'Active'),
                array('id' => '26','name' => 'GOLD','description' => 'GOLG Plan','tagline' => 'gold','duration' => '0','no_of_product' => '0','custom_plan' => 'Yes','is_free' => 'No','price' => '0.00','currency' => '','status' => 'Active'),
                array('id' => '27','name' => '5 Plan','description' => 'TEst','tagline' => 'Test','duration' => '1','no_of_product' => '3','custom_plan' => 'No','is_free' => 'No','price' => '5.00','currency' => 'INR','status' => 'Active'),
                array('id' => '29','name' => 'Master Plan','description' => 'Master pilan','tagline' => 'Master plan in 70 INR','duration' => '1','no_of_product' => '2','custom_plan' => 'No','is_free' => 'No','price' => '75.00','currency' => 'INR','status' => 'Active'),
                array('id' => '30','name' => 'Stripe plan','description' => 'One Day stripe plan','tagline' => 'Stripe plan','duration' => '1','no_of_product' => '2','custom_plan' => 'No','is_free' => 'No','price' => '30.00','currency' => 'INR','status' => 'Active'),
                array('id' => '31','name' => 'free','description' => 'free','tagline' => 'free','duration' => '1','no_of_product' => '1','custom_plan' => 'No','is_free' => 'Yes','price' => '0.00','currency' => '','status' => 'Active'),
                array('id' => '32','name' => '2 Product Plan Free','description' => '2 Product Plan Free','tagline' => '2 Product Plan Free','duration' => '1','no_of_product' => '2','custom_plan' => 'No','is_free' => 'Yes','price' => '0.00','currency' => '','status' => 'Active'),
                array('id' => '33','name' => 'Elite','description' => 'One of the best plan','tagline' => 'One of the best plan','duration' => '1','no_of_product' => '10','custom_plan' => 'No','is_free' => 'No','price' => '250.00','currency' => 'USD','status' => 'Active')
			),
        );
    }
}
