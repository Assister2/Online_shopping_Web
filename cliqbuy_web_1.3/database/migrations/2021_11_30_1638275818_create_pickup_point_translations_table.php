<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupPointTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('pickup_point_translations', function (Blueprint $table) {

		$table->bigInteger('id',20);
		$table->bigInteger('pickup_point_id');
		$table->string('name',50);
		$table->text('address');
		$table->string('lang',100);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('pickup_point_translations');
    }
}