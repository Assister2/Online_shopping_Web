<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('name');
		$table->string('symbol');
        $table->double('exchange_rate',10,5);
		$table->integer('status')->default('0');
		$table->string('code',20)->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}