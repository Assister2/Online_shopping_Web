<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {

		$table->char('id',36);
		$table->string('type',191);
		$table->string('notifiable_type',191);
		$table->bigInteger('notifiable_id')->unsigned();
		$table->text('data');
		$table->timestamp('read_at')->nullable();
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}