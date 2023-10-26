<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('photo')->nullable()->default(NULL);
		$table->string('url',1000)->nullable()->default(NULL);
		$table->integer('position')->default('1');
		$table->integer('published')->default('0');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('banners');
    }
}