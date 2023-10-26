<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonsTable extends Migration
{
    public function up()
    {
        Schema::create('addons', function (Blueprint $table) {

		$table->increments('id');
		$table->string('name')->nullable()->default(NULL);
		$table->string('unique_identifier')->nullable()->default(NULL);
		$table->string('version')->nullable()->default(NULL);
		$table->integer('activated')->default('1');
		$table->string('image',1000)->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('addons');
    }
}