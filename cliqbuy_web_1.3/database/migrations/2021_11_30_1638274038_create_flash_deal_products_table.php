<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashDealProductsTable extends Migration
{
    public function up()
    {
        Schema::create('flash_deal_products', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('flash_deal_id');
		$table->integer('product_id');
        $table->double('discount',20,2)->default(0.00);
		$table->string('discount_type',20)->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('flash_deal_products');
    }
}