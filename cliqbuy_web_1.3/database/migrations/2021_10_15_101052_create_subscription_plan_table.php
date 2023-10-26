<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100);
            $table->text('description');
            $table->string('tagline',50);
            $table->integer('duration');
            $table->integer('no_of_product');            
            $table->enum('custom_plan',['Yes', 'No'])->default('No');
            $table->enum('is_free',['Yes', 'No'])->default('No');
            $table->decimal('price',8,2);
            $table->string('currency',5)->nullable();
            $table->enum('status',['Active', 'Inactive'])->default('Active');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_plan');
    }
}
