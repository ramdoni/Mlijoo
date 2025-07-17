<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceTransaksiItem;

class InvoiceTransaksi extends Model
{
    use HasFactory;

    protected $table = 'invoice_transaksi';

    public function items()
    {
        return $this->hasMany(InvoiceTransaksiItem::class,'invoice_transaksi_id','id');
    }
}
