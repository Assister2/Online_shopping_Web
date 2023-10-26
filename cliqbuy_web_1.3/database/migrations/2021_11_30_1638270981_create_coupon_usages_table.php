<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponUsagesTable extends Migration
{
    public function up()
    {
        Schema::create('coupon_usages', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('user_id');
		$table->integer('coupon_id');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_usages');
    }
}