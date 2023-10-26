<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('user_id');
		$table->string('name',200)->nullable()->default(NULL);
		$table->string('logo')->nullable()->default(NULL);
        $table->longText('sliders')->nullable();
		$table->string('phone')->nullable()->default(NULL);
		$table->string('address',500)->nullable()->default(NULL);
		$table->string('country',30)->nullable();
        $table->string('city',30)->nullable();
        $table->string('postal_code',20)->nullable();
		$table->string('facebook')->nullable()->default(NULL);
		$table->string('google')->nullable()->default(NULL);
		$table->string('twitter')->nullable()->default(NULL);
		$table->string('youtube')->nullable()->default(NULL);
		$table->string('slug')->nullable()->default(NULL);
		$table->string('meta_title')->nullable()->default(NULL);
		$table->text('meta_description')->nullable()->default(NULL);
		$table->text('pick_up_point_id')->nullable()->default(NULL);
        $table->double('shipping_cost',20,2)->default('0.00');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('shops');
    }
}