<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owe_amounts', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->integer('seller_id');
            $table->integer('total_amount');
            $table->integer('remain_amount');
            $table->integer('paid_amount');
            $table->enum('status',['Pending', 'Completed']);
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
        Schema::dropIfExists('owe_amounts');
    }
};
