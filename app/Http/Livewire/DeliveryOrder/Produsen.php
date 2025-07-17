<?php

namespace App\Http\Livewire\DeliveryOrder;

use App\Models\ProdusenDeliveryOrder;
use Livewire\Component;
use Livewire\WithPagination;

class Produsen extends Component
{
    use WithPagination;
    public $totals=[
        'delivery_order_hari_ini'=>0,
        'pending'=>0,
        'pengiriman_bulan_ini'=>0,
        'pengiriman_bulan_ini_rp'=>0
    ];
    public function render()
    {
        $data = ProdusenDeliveryOrder::orderBy('id','DESC');

        return view('livewire.delivery-order.produsen')->with(['data'=>$data->paginate(100)]);
    }
}
