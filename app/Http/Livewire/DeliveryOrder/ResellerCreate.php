<?php

namespace App\Http\Livewire\DeliveryOrder;

use Livewire\Component;

class ResellerCreate extends Component
{
    public $form=[
        'reseller_id',
        'no_delivery_order',
        'pengirim_nama',
        'pengirim_no_telepon',
        'total'        
    ];

    public function render()
    {
        return view('livewire.delivery-order.reseller-create');
    }

    public function save()
    {
        $this->validate([
            'form.no_delivery_order' =>'required',
            'form.pengirim_nama'=>'required',
            'form.pengirim_no_telepon'=>'required'
        ]);

        
    }
}
