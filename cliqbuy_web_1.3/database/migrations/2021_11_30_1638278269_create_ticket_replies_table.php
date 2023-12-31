<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketRepliesTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_replies', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('ticket_id');
		$table->integer('user_id');
        $table->longText('reply');
        $table->longText('files')->nullable();
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_replies');
    }
}