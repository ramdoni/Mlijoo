<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdFieldAtKelurahanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelurahan', function (Blueprint $table) {
            $table->renameColumn('id_kel', 'id');
            $table->renameColumn('id_kec', 'kecamatan_id');
        });

        Schema::table('kelurahan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement()->change();
            $table->unsignedBigInteger('kecamatan_id')->change();
            $table->foreign('kecamatan_id')->references('id')->on('kecamatan')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelurahan', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
        });

        Schema::table('kelurahan', function (Blueprint $table) {
            $table->renameColumn('id', 'id_kel');
            $table->renameColumn('kecamatan_id', 'id_kec');
        });

        Schema::table('kelurahan', function (Blueprint $table) {
            $table->string('id_kel', 2)->change();
            $table->string('id_kec', 2)->change();
        });
    }
}
