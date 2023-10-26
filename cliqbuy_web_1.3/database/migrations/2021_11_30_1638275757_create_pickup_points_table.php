<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupPointsTable extends Migration
{
    public function up()
    {
        Schema::create('pickup_points', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('staff_id');
		$table->string('name');
        $table->mediumText('address');
		$table->string('phone',15);
		$table->integer('pick_up_status')->nullable();
		$table->integer('cash_on_pickup_status')->nullable();
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('pickup_points');
    }
}