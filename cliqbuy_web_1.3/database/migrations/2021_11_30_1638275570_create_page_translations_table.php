<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('page_translations', function (Blueprint $table) {

		$table->bigInteger('id',20);
		$table->bigInteger('page_id');
		$table->string('title');
        $table->longText('content')->nullable()->default(NULL);
		$table->string('lang',100);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('page_translations');
    }
}