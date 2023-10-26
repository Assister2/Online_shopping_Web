<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlanTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plan_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subscription_plan_id')->unsigned();
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plan');
            $table->string('name',100);
            $table->text('description');
            $table->string('tagline',50);
            $table->string('locale',5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_plan_translations');
    }
}
