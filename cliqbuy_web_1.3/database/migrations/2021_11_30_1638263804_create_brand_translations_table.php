<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('brand_translations', function (Blueprint $table) {

		$table->bigInteger('id',20);
		$table->bigInteger('brand_id');
		$table->string('name',50);
		$table->string('lang',100);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('brand_translations');
    }
}