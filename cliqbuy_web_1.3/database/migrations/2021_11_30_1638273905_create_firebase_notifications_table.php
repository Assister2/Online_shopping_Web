<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirebaseNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('firebase_notifications', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('title')->nullable()->default(NULL);
		$table->text('text')->nullable()->default(NULL);
		$table->string('item_type');
		$table->integer('item_type_id');
		$table->integer('receiver_id');
		$table->tinyInteger('is_read')->default('0');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('firebase_notifications');
    }
}