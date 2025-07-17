<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProdusenInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produsen_invoices', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(0)->nullable();
            $table->integer('produsen_id')->nullable();
            $table->integer('produsen_purchase_order_id')->nullable();
            $table->string('no_invoice',100)->nullable();
            $table->bigInteger('total_amount')->nullable();
            $table->boolean('metode_pembayaran')->default(1)->nullable();
            $table->text('metode_pembayaran_file')->nullable();
            $table->date('due_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->integer('user_created_id')->nullable();
            $table->integer('user_process_id')->nullable();
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
        Schema::dropIfExists('produsen_invoice');
    }
}
