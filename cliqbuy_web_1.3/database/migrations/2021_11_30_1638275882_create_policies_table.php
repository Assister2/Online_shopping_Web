<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliciesTable extends Migration
{
    public function up()
    {
        Schema::create('policies', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('name',35);
        $table->longText('content')->nullable();
		$table->timestamp('created_at');
		$table->timestamp('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('policies');
    }
}