<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableResellerDeliveryOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseller_delivery_order', function (Blueprint $table) {
            $table->id();
            $table->integer('reseller_id')->nullable();
            $table->string('no_delivery_order',100)->nullable();
            $table->string('pengirim_nama',100)->nullable();
            $table->string('pengirim_no_telepon',50)->nullable();
            $table->integer('total')->nullable();
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
        Schema::dropIfExists('reseller_delivery_order');
    }
}
