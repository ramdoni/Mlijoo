<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductUom;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'products', $guarded = [],$dates = ['deleted_at'];  
    
    public function uom()
    {
        return $this->hasOne(ProductUom::class,'id','product_uom_id');
    }

    public function reseller()
    {
        return $this->hasOne(Reseller::class,'id','reseller_id');
    }
}