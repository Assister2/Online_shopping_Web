<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('city_translations', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('city_id');
		$table->string('name');
		$table->string('lang',10);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('city_translations');
    }
}