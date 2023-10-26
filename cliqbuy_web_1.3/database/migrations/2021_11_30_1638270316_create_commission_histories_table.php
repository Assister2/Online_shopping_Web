<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('commission_histories', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('order_id');
		$table->integer('order_detail_id');
		$table->integer('seller_id');
        $table->double('admin_commission',25,2);
        $table->double('seller_earning',25,2);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('commission_histories');
    }
}