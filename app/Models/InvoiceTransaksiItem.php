<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class InvoiceTransaksiItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_transaksi_item';

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class,'id','transaksi_id');
    }
}
