<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('home_categories', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('category_id');
		$table->string('subsubcategories',1000)->nullable()->default(NULL);
		$table->integer('status')->default('1');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('home_categories');
    }
}