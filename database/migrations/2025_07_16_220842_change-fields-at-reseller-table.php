<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsAtResellerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resellers', function (Blueprint $table) {
            $table->dropColumn(['provinsi_id', 'kabupaten_id', 'kecamatan_id', 'pic_nama', 'pic_phone']);
            $table->unsignedBigInteger('kelurahan_id')->change();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('kelurahan_id')->references('id')->on('kelurahan')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resellers', function (Blueprint $table) {
            $table->dropForeign(['kelurahan_id']);
            $table->dropColumn(['user_id']);

            $table->integer('provinsi_id')->nullable();
            $table->integer('kabupaten_id')->nullable();
            $table->integer('kecamatan_id')->nullable();
            $table->string('pic_nama', 50)->nullable();
            $table->string('pic_phone', 20)->nullable();
        });
    }
}
