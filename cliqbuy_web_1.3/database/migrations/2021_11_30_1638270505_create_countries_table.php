<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('code',2)->default('');
		$table->string('name',100)->default('');
		$table->integer('status')->default('1');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('countries');
    }
}