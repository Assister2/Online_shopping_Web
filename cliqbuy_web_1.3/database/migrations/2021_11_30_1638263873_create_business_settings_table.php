<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('business_settings', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('type',30);
        $table->longText('value')->nullable();
		$table->string('lang',30)->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('business_settings');
    }
}