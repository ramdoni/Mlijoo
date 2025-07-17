<?php

namespace App\Http\Livewire\Produsen;

use App\Models\ProdusenDeliveryOrder;
use Livewire\Component;

class DeliveryOrder extends Component
{
    public function render()
    {
        $data = ProdusenDeliveryOrder::orderBy('id','DESC');
        
        return view('livewire.produsen.delivery-order')->with(['data'=>$data->paginate(100)]);
    }
}
