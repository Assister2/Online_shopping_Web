<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('product_id');
		$table->integer('user_id');
		$table->integer('rating')->default('0');
        $table->mediumText('comment');
		$table->integer('status')->default('1');
		$table->integer('viewed')->default('0');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}