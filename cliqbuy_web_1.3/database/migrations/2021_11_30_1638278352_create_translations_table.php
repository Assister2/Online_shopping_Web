<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('lang',10)->nullable()->default(NULL);
		$table->text('lang_key')->nullable()->default(NULL);
		$table->text('lang_value')->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('translations');
    }
}