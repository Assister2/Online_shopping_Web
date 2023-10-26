<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('user_id');
        $table->double('amount',20,2);
		$table->string('payment_method')->nullable()->default(NULL);
        $table->longText('payment_details')->nullable();
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}