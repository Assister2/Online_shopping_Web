<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {

		$table->bigInteger('id',20)->unsigned();
		$table->integer('category_id');
		$table->string('title');
		$table->string('slug');
		$table->text('short_description')->nullable()->default(NULL);
		$table->longText('description')->nullable()->default(NULL);
		$table->integer('banner')->nullable();
		$table->string('meta_title')->nullable()->default(NULL);
		$table->integer('meta_img')->nullable();
		$table->text('meta_description')->nullable()->default(NULL);
		$table->text('meta_keywords')->nullable()->default(NULL);
		$table->integer('status')->default('1');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');
		$table->timestamp('deleted_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}