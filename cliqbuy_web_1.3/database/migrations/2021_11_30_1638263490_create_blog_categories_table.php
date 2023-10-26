<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {

		$table->bigInteger('id',20)->unsigned();
		$table->string('category_name');
		$table->string('slug');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');
		$table->timestamp('deleted_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_categories');
    }
}