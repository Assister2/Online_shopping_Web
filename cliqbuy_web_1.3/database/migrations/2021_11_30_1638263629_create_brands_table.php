<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('name',50);
		$table->string('logo',100)->nullable()->default(NULL);
		$table->integer('top')->default('0');
		$table->string('slug')->nullable()->default(NULL);
		$table->string('meta_title')->nullable()->default(NULL);
		$table->text('meta_description')->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('brands');
    }
}