<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableResellerProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseller_products', function (Blueprint $table) {
            $table->id();
            $table->integer('reseller_id')->nullable();
            $table->string('sku',100)->nullable();
            $table->string('nama',220)->nullable();
            $table->string('type',100)->nullable();
            $table->integer('harga',)->nullable();
            $table->string('alias',150)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(1)->nullable();
            $table->integer('stock')->default(0)->nullable();
            $table->integer('product_uom_id')->default(0)->nullable();
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
        Schema::dropIfExists('reseller_products');
    }
}
