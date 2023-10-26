<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerWithdrawRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('seller_withdraw_requests', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('user_id')->nullable();
        $table->double('amount',20,2)->nullable();
        $table->longText('message')->nullable();
		$table->integer('status')->nullable();
		$table->integer('viewed')->nullable();
        $table->integer('currency_id')->nullable();
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('seller_withdraw_requests');
    }
}