<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdFieldAtProvinsiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provinsi', function (Blueprint $table) {
            $table->renameColumn('id_prov', 'id');
        });

        Schema::table('provinsi', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provinsi', function (Blueprint $table) {
            $table->renameColumn('id', 'id_prov');
        });

        Schema::table('provinsi', function (Blueprint $table) {
            $table->string('id_prov', 2)->change();
        });
    }
}
