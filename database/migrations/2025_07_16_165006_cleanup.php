<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cleanup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dropTables = [
            "iuran",
            "jenis_pinjaman",
            "jenis_simpanan",
            "modules",
            "modules_items",
            "pinjaman",
            "pinjaman_items",
            "product_operator",
            "projects",
            "rekomendator_attachment",
            "simpanan",
            "supplier",
            "supplier_product",
            "toko",
            "transaksi_simpanan",
            "transaksi_simpanan_wajib",
            "uang_pendaftaran",
            "user_member",
            "user_member_simpanan",
            "user_member_simpanan_wajib",
            "vendor",
        ];

        foreach ($dropTables as $table) {
            Schema::dropIfExists($table);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
