<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdFieldAtKabupatenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kabupaten', function (Blueprint $table) {
            $table->renameColumn('id_kab', 'id');
            $table->renameColumn('id_prov', 'provinsi_id');
        });

        Schema::table('kabupaten', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement()->change();
            $table->unsignedBigInteger('provinsi_id')->change();
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kabupaten', function (Blueprint $table) {
            $table->dropForeign(['provinsi_id']);
        });

        Schema::table('kabupaten', function (Blueprint $table) {
            $table->renameColumn('id', 'id_kab');
            $table->renameColumn('provinsi_id', 'id_prov');
        });

        Schema::table('kabupaten', function (Blueprint $table) {
            $table->string('id_kab', 2)->change();
            $table->string('id_prov', 2)->change();
        });
    }
}
