<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerProductsTable extends Migration
{
    public function up()
    {
        Schema::create('customer_products', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('name')->nullable()->default(NULL);
		$table->integer('published')->default('0');
		$table->integer('status')->default('0');
		$table->string('added_by',50)->nullable()->default(NULL);
		$table->integer('user_id')->nullable();
		$table->integer('category_id')->nullable();
		$table->integer('subcategory_id')->nullable();
		$table->integer('subsubcategory_id')->nullable();
		$table->integer('brand_id')->nullable();
		$table->string('photos')->nullable()->default(NULL);
		$table->string('thumbnail_img',150)->nullable()->default(NULL);
		$table->string('conditon',50)->nullable()->default(NULL);
		$table->text('location')->nullable()->default(NULL);
		$table->string('video_provider',100)->nullable()->default(NULL);
		$table->string('video_link',200)->nullable()->default(NULL);
		$table->string('unit',200)->nullable()->default(NULL);
		$table->string('tags')->nullable()->default(NULL);
		$table->mediumText('description')->nullable()->default(NULL);
		$table->double('unit_price',20,2)->default(0.00);
		$table->string('meta_title',200)->nullable()->default(NULL);
		$table->string('meta_description',500)->nullable()->default(NULL);
		$table->string('meta_img',150)->nullable()->default(NULL);
		$table->string('pdf',200)->nullable()->default(NULL);
		$table->string('slug',200)->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_products');
    }
}