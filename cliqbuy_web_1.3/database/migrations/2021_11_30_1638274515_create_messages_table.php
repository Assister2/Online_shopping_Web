<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('conversation_id');
		$table->integer('product_id')->nullable()->default(NULL);
        $table->integer('user_id');
		$table->text('message')->nullable()->default(NULL);
        $table->text('product_link')->nullable()->default(NULL);
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}