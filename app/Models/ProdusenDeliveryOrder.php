<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdusenDeliveryOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function produsen()
    {
        return $this->hasOne(Produsen::class,'id','produsen_id');
    }   

    public function purchase_order()
    {
        return $this->hasOne(PurchaseOrder::class,'id','purchase_order_id');
    }
}
