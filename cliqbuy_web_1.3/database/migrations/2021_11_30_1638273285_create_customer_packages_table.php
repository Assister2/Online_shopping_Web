<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPackagesTable extends Migration
{
    public function up()
    {
        Schema::create('customer_packages', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('name')->nullable()->default(NULL);
		$table->integer('product_upload')->nullable();
		$table->string('logo',150)->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_packages');
    }
}