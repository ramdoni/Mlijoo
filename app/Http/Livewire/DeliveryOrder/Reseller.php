<?php

namespace App\Http\Livewire\DeliveryOrder;

use App\Models\ResellerDeliveryOrder;
use Livewire\Component;
use Livewire\WithPagination;

class Reseller extends Component
{
    use WithPagination;
    public $totals = [
        'delivery_order_hari_ini'=>0,
        'pending'=>0,
        'pengiriman_bulan_ini'=>0,
        'pengiriman_bulan_ini_rp'=>0
    ];
    public function render()
    {
        $data = ResellerDeliveryOrder::orderBy('id','DESC');

        return view('livewire.delivery-order.reseller')->with(['data'=>$data->paginate(100)]);
    }
}
