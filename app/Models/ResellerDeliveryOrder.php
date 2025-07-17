<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerDeliveryOrder extends Model
{
    use HasFactory;

    protected $table = 'reseller_delivery_order',$guarded = ['id'];
}
