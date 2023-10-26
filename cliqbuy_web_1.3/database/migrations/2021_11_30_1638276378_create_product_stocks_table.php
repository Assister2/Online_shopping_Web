<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStocksTable extends Migration
{
    public function up()
    {
        Schema::create('product_stocks', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('product_id');
		$table->string('variant');
		$table->string('sku')->nullable()->default(NULL);
		$table->double('price',20,2)->default('0.00');
		$table->integer('qty')->default('0');
		$table->integer('image')->nullable();
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('product_stocks');
    }
}