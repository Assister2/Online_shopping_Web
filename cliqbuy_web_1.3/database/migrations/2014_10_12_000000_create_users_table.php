<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
          
            $table->integer('referred_by')->nullable();
            $table->string('provider_id',50)->nullable();
            $table->enum('user_type',['seller','admin','staff','customer'])->default('customer');
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->text('verification_code')->nullable();
            $table->text('new_email_verificiation_code')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('device_token',256)->nullable();
            $table->string('avatar',256)->nullable();
            $table->string('avatar_original',256)->nullable();
            $table->string('address',300)->nullable();
            $table->string('country',30)->nullable();
            $table->string('city',30)->nullable();
            $table->string('postal_code',20)->nullable();
            $table->string('phone',20)->nullable();
            $table->double('balance',20,2)->default(0.00);
            $table->tinyInteger('banned')->default(0);
            $table->string('referral_code',256)->nullable();
            $table->integer('customer_package_id')->nullable();
            $table->integer('remaining_uploads')->default(0)->nullable();
            $table->integer('otp')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
