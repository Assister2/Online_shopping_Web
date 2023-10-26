<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashDealsTable extends Migration
{
    public function up()
    {
        Schema::create('flash_deals', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('title')->nullable();
		$table->integer('start_date')->nullable();
		$table->integer('end_date')->nullable();
		$table->integer('status')->default('0');
		$table->integer('featured')->default('0');
		$table->string('background_color')->nullable()->default(NULL);
		$table->string('text_color')->nullable()->default(NULL);
		$table->string('banner')->nullable()->default(NULL);
		$table->string('slug')->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('flash_deals');
    }
}