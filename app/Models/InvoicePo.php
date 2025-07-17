<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoicePoItem;

class InvoicePo extends Model
{
    use HasFactory;

    protected $table = 'invoice_po';

    public function items()
    {
        return $this->hasMany(InvoicePoItem::class,'invoice_po_id','id');
    }
}
