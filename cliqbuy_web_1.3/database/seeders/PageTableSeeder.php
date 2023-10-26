<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('pages')->delete();

        \DB::table('pages')->insert(
        	array(
			  array('id' => '1','type' => 'home_page','url' => NULL,'title' => 'Home Page','slug' => 'home','content' => NULL,'meta_title' => NULL,'meta_description' => NULL,'keywords' => NULL,'meta_image' => NULL,'created_at' => '2020-11-04 15:43:20','updated_at' => '2020-11-04 15:43:20'),
			  array('id' => '2','type' => 'seller_policy_page','url' => 'sellerpolicy','title' => 'Seller Policy Pages','slug' => 'sellerpolicy','content' => NULL,'meta_title' => NULL,'meta_description' => NULL,'keywords' => NULL,'meta_image' => NULL,'created_at' => '2020-11-04 15:44:41','updated_at' => '2020-11-04 17:49:30'),
			  array('id' => '4','type' => 'support_policy_page','url' => 'supportpolicy','title' => 'Support Policy Page','slug' => 'supportpolicy','content' => NULL,'meta_title' => NULL,'meta_description' => NULL,'keywords' => NULL,'meta_image' => NULL,'created_at' => '2020-11-04 15:44:59','updated_at' => '2020-11-04 15:44:59'),
			  array('id' => '5','type' => 'terms_conditions_page','url' => 'terms','title' => 'Terms & Conditions','slug' => 'terms','content' => NULL,'meta_title' => NULL,'meta_description' => NULL,'keywords' => NULL,'meta_image' => NULL,'created_at' => '2020-11-04 15:45:29','updated_at' => '2020-11-04 15:45:29'),
			  array('id' => '6','type' => 'privacy_policy_page','url' => 'privacypolicy','title' => 'Privacy Policy ','slug' => 'privacypolicy','content' => NULL,'meta_title' => NULL,'meta_description' => NULL,'keywords' => NULL,'meta_image' => NULL,'created_at' => '2020-11-04 15:45:55','updated_at' => '2020-11-04 15:45:55'),
              array('id' => '7','type' => 'about_us_page','url' => 'aboutus','title' => 'About us','slug' => 'aboutus','content' => NULL,'meta_title' => NULL,'meta_description' => NULL,'keywords' => NULL,'meta_image' => NULL,'created_at' => '2020-11-04 15:45:55','updated_at' => '2020-11-04 15:45:55'),
              array('id' => '8','type' => 'help_page','url' => 'help','title' => 'Help','slug' => 'help','content' => NULL,'meta_title' => NULL,'meta_description' => NULL,'keywords' => NULL,'meta_image' => NULL,'created_at' => '2020-11-04 15:45:55','updated_at' => '2020-11-04 15:45:55')
			),
        );
    }
}
