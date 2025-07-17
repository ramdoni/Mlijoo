<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProdusenDeliveryOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produsen_delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('produsen_id')->nullable();
            $table->integer('produsen_purchase_order_id')->nullable();
            $table->string('no_delivery_order',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produsen_delivery_orders');
    }
}
