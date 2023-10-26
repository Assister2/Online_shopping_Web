<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('code');
		$table->integer('user_id');
		$table->string('subject');
        $table->longText('details')->nullable();
        $table->longText('files')->nullable();
		$table->string('status',10)->default('pending');
		$table->integer('viewed')->default('0');
		$table->integer('client_viewed')->default('0');
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}