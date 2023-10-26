<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionRenewalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscription_renewal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('user_plan_id')->unsigned();
            // $table->foreign('user_plan_id')->references('id')->on('user_subscription_plan');
            $table->string('transaction_id',250)->nullable();
            $table->string('subscription_id',250)->nullable();
            $table->string('name',100)->nullable();
            $table->text('description')->nullable();
            $table->string('tagline',50)->nullable();
            $table->integer('duration');
            $table->integer('no_of_product'); 
            $table->enum('plan_type', ['Custom','Free','Paid'])->default('Custom');
            $table->decimal('price',8,2);
            $table->string('currency',5)->nullable();
            $table->enum('payment_type', ['stripe','paypal'])->nullable();
            $table->enum('payment_status', ['Pending','Success','Failed'])->default('Pending');
            $table->string('flow_type',10)->nullable();
            $table->integer('cancelled');
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
        Schema::dropIfExists('user_subscription_renewal');
    }
}
