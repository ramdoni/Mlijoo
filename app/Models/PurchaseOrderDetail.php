<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\ProductUom;

class PurchaseOrderDetail extends Model
{
    use HasFactory;
    protected $table = 'purchase_order_detail';
    protected $guarded = [];  
    
    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function uom()
    {
        return $this->hasOne(ProductUom::class,'id','product_uom_id');
    }
}
