<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('product_translations', function (Blueprint $table) {

		$table->bigInteger('id',20);
		$table->bigInteger('product_id');
		$table->string('name',200)->nullable()->default(NULL);
		$table->string('unit',20)->nullable()->default(NULL);
        $table->longText('description')->nullable();
		$table->string('lang',100);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('product_translations');
    }
}