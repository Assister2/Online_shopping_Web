<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('seller_id');
        $table->double('amount',20,2)->default('0.00');
        $table->longText('payment_details')->nullable();
		$table->string('payment_method')->nullable()->default(NULL);
		$table->string('txn_code',100)->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}