<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {

		$table->increments('id');
		$table->integer('user_id');
		$table->string('address')->nullable()->default(NULL);
		$table->string('country')->nullable()->default(NULL);
		$table->string('city')->nullable()->default(NULL);
		$table->float('longitude')->nullable();
		$table->float('latitude')->nullable();
		$table->string('postal_code')->nullable()->default(NULL);
		$table->string('phone')->nullable()->default(NULL);
		$table->integer('set_default')->default('0');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}