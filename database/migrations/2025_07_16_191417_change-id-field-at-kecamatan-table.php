<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdFieldAtKecamatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kecamatan', function (Blueprint $table) {
            $table->renameColumn('id_kec', 'id');
            $table->renameColumn('id_kab', 'kabupaten_id');
        });

        Schema::table('kecamatan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement()->change();
            $table->unsignedBigInteger('kabupaten_id')->change();
            $table->foreign('kabupaten_id')->references('id')->on('kabupaten')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kecamatan', function (Blueprint $table) {
            $table->dropForeign(['kabupaten_id']);
        });

        Schema::table('kecamatan', function (Blueprint $table) {
            $table->renameColumn('id', 'id_kec');
            $table->renameColumn('kabupaten_id', 'id_kab');
        });

        Schema::table('kecamatan', function (Blueprint $table) {
            $table->string('id_kec', 2)->change();
            $table->string('id_kab', 2)->change();
        });
    }
}
