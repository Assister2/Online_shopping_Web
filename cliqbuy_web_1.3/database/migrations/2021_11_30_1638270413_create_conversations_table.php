<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('sender_id');
		$table->integer('receiver_id');
		$table->string('title',1000)->nullable()->default(NULL);
		$table->integer('sender_viewed')->default('1');
		$table->integer('receiver_viewed')->default('0');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}