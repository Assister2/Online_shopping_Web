<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {

		$table->integer('id',11)->unsigned();
		$table->integer('owner_id')->nullable();
		$table->integer('user_id')->nullable();
		$table->string('temp_user_id')->nullable()->default(NULL);
		$table->integer('address_id')->default('0');
		$table->integer('product_id')->nullable();
		$table->text('variation')->nullable()->default(NULL);
        $table->double('price',8,2)->default(0.00);
        $table->double('tax',8,2)->default(0.00);
        $table->double('shipping_cost',8,2)->default(0.00);
		$table->string('shipping_type',30)->default('');
		$table->integer('pickup_point')->nullable();
        $table->double('discount',10,2)->default(0.00);
		$table->string('product_referral_code')->nullable()->default(NULL);
		$table->string('coupon_code');
		$table->tinyInteger('coupon_applied')->default('0');
		$table->integer('quantity')->default('0');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
}