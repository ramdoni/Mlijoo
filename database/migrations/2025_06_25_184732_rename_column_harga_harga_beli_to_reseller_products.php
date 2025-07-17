<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnHargaHargaBeliToResellerProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reseller_products', function (Blueprint $table) {
            $table->renameColumn('harga','harga_beli');
            $table->integer('harga_jual')->nullable();
            $table->integer('margin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reseller_products', function (Blueprint $table) {
            //
        });
    }
}
