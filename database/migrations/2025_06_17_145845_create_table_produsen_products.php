<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProdusenProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produsen_products', function (Blueprint $table) {
            $table->id();
            $table->integer('produsen_id')->nullable();
            $table->string('sku',100)->nullable();
            $table->string('nama',220)->nullable();
            $table->string('type',100)->nullable();
            $table->integer('harga',)->nullable();
            $table->string('alias',150)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(1)->nullable();
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
        Schema::dropIfExists('produsen_products');
    }
}
