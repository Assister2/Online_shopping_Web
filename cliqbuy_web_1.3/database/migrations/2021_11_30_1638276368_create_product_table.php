<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('name',200);
		$table->string('added_by',6);
		$table->integer('user_id');
		$table->integer('category_id');
		$table->integer('brand_id')->nullable();
		$table->string('photos',2000)->nullable();
		$table->string('thumbnail_img',100)->nullable();
		$table->string('video_provider',20)->nullable();
		$table->string('video_link',100)->nullable();
		$table->string('tags',1000)->nullable();
		$table->longText('description')->nullable();
		$table->double('unit_price',20,2);
		$table->double('purchase_price',20,2)->nullable();
		$table->integer('variant_product')->default('0');
		$table->string('attributes',1000);
		$table->mediumText('choice_options')->nullable();
		$table->mediumText('colors')->nullable();
		$table->text('variations')->nullable();
		$table->integer('todays_deal')->default('0');
		$table->integer('published')->default('1');
		$table->tinyInteger('approved')->default('1');
		$table->string('stock_visibility_state',10)->default('quantity');
		$table->tinyInteger('cash_on_delivery')->default('1');
		$table->integer('featured')->default('0');
		$table->integer('seller_featured')->default('0');
		$table->integer('current_stock')->default('0');
		$table->string('unit',20)->nullable();
		$table->integer('min_qty')->default('1');
		$table->integer('low_stock_quantity')->nullable();
		$table->double('discount',20,2)->nullable();
		$table->string('discount_type',10)->nullable();
		$table->integer('discount_start_date')->nullable();
		$table->integer('discount_end_date')->nullable();
		$table->double('tax',20,2)->nullable();
		$table->string('tax_type',10)->nullable();
		$table->string('shipping_type',30)->nullable()->default('flat_rate');
		$table->text('shipping_cost')->nullable();
		$table->tinyInteger('is_quantity_multiplied')->default('0');
		$table->integer('est_shipping_days')->nullable();
		$table->integer('num_of_sale')->default('0');
		$table->mediumText('meta_title')->nullable();
		$table->longText('meta_description')->nullable();
		$table->string('meta_img')->nullable();
		$table->string('pdf')->nullable();
		$table->mediumText('slug');
		$table->double('rating',8,2)->default('0.00');
		$table->string('barcode')->nullable();
		$table->integer('digital')->default('0');
		$table->integer('auction_product')->default('0');
		$table->string('file_name')->nullable();
		$table->string('file_path')->nullable();
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('product_stocks');
    }
}