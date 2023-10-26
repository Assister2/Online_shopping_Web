<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('parent_id')->default('0');
		$table->integer('level')->default('0');
		$table->string('name',50);
		$table->integer('order_level')->default('0');
        $table->double('commision_rate',8,2)->default(0.00);
		$table->string('banner',100)->nullable()->default(NULL);
		$table->string('icon',100)->nullable()->default(NULL);
		$table->integer('featured')->default('0');
		$table->integer('top')->default('0');
		$table->integer('digital')->default('0');
		$table->string('slug')->nullable()->default(NULL);
		$table->string('meta_title')->nullable()->default(NULL);
		$table->text('meta_description')->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}