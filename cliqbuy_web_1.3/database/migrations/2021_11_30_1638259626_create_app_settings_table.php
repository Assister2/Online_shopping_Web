<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('app_settings', function (Blueprint $table) {

		$table->integer('id',11)->unsigned();
		$table->string('name')->nullable()->default(NULL);
		$table->string('logo')->nullable()->default(NULL);
		$table->integer('currency_id')->nullable();
		$table->char('currency_format')->nullable();
		$table->string('facebook')->nullable()->default(NULL);
		$table->string('twitter')->nullable()->default(NULL);
		$table->string('instagram')->nullable()->default(NULL);
		$table->string('youtube')->nullable()->default(NULL);
		$table->string('google_plus')->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('app_settings');
    }
}