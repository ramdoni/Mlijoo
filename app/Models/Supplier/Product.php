<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier\User;

class Product extends Model
{
    use HasFactory;

    protected $table='products';
    
    protected $connection = 'supplier';

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function uom()
    {
        return $this->hasOne(ProductUom::class,'id','product_uom_id');
    }
}
