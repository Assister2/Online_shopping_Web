<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscription_plan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('subscription_plan_id');
            $table->string('name',100);
            $table->text('description');
            $table->string('tagline',50);
            $table->integer('duration');
            $table->integer('no_of_product');
            $table->enum('plan_type',['Custom','Free','Paid'])->default('Custom');
            $table->decimal('price',8,2);
            $table->string('currency',5);
            $table->string('customer_id',250)->nullable();
            $table->enum('auto_renewal',['On','Off'])->default('On');
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->string('flow_type',10)->nullable();
            $table->integer('alert_subscription');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subscription_plan');
    }
}
