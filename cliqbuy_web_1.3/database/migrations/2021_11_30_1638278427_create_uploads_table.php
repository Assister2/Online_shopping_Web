<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('file_original_name',255)->nullable()->default(NULL);
		$table->string('file_name',255)->nullable()->default(NULL);
		$table->integer('user_id')->nullable();
		$table->integer('file_size')->nullable();
		$table->string('extension',10)->nullable()->default(NULL);
		$table->string('type',15)->nullable()->default(NULL);
		$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		$table->timestamp('deleted_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}