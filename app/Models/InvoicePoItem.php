<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseOrder;

class InvoicePoItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_po_item';

    public function purchase_order()
    {
        return $this->hasOne(PurchaseOrder::class,'no_po','invoice_po_id');
    }
}
