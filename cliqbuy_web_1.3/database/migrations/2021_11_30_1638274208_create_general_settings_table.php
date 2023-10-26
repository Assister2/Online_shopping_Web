<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('frontend_color')->default('default');
		$table->string('logo')->nullable()->default(NULL);
		$table->string('footer_logo')->nullable()->default(NULL);
		$table->string('admin_logo')->nullable()->default(NULL);
		$table->string('admin_login_background')->nullable()->default(NULL);
		$table->string('admin_login_sidebar')->nullable()->default(NULL);
		$table->string('favicon')->nullable()->default(NULL);
		$table->string('site_name')->nullable()->default(NULL);
		$table->string('address',1000)->nullable()->default(NULL);
		$table->mediumText('description');
		$table->string('phone',100)->nullable()->default(NULL);
		$table->string('email')->nullable()->default(NULL);
		$table->string('facebook',1000)->nullable()->default(NULL);
		$table->string('instagram',1000)->nullable()->default(NULL);
		$table->string('twitter',1000)->nullable()->default(NULL);
		$table->string('youtube',1000)->nullable()->default(NULL);
		$table->string('google_plus',1000)->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('general_settings');
    }
}