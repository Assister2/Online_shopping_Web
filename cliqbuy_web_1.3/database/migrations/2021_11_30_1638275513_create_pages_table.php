<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('type',50);
		$table->string('url',191)->nullable();
		$table->string('title')->nullable()->default(NULL);
		$table->string('slug')->nullable()->default(NULL);
		$table->longText('content')->nullable()->default(NULL);
		$table->text('meta_title')->nullable()->default(NULL);
		$table->string('meta_description',1000)->nullable()->default(NULL);
		$table->string('keywords',1000)->nullable()->default(NULL);
		$table->string('meta_image')->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
}