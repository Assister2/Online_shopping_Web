<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashDealTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('flash_deal_translations', function (Blueprint $table) {

		$table->bigInteger('id',20);
		$table->bigInteger('flash_deal_id');
		$table->string('title',50);
		$table->string('lang',100);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('flash_deal_translations');
    }
}