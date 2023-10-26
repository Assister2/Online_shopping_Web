<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProxypayPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('proxypay_payments', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('payment_type',20);
		$table->string('reference_id',20);
		$table->integer('order_id')->nullable();
		$table->integer('package_id')->nullable();
		$table->integer('user_id');
		$table->double('price',25,2)->default('0.00');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('proxypay_payments');
    }
}