<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->string('rate_id')->after('shipping_type')->nullable();
            $table->string('service_code', 100)->after('shipping_type')->nullable();
            $table->string('package_type', 100)->after('service_code')->nullable();
            $table->string('tracking_number', 100)->after('package_type')->nullable();
            $table->enum('tracking_type', ['manual', 'ship_engine'])->after('package_type')->default('manual');
            $table->string('label_download')->after('tracking_number')->nullable();
            $table->string('label_id')->after('label_download')->nullable();
            $table->string('carrier_name', 50)->after('quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            //
        });
    }
};
