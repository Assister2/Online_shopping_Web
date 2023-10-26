<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('attribute_translations', function (Blueprint $table) {

		$table->bigInteger('id',20);
		$table->bigInteger('attribute_id');
		$table->string('name',50);
		$table->string('lang',100);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('attribute_translations');
    }
}