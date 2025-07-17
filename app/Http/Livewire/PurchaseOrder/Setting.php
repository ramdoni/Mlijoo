<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;

class Setting extends Component
{
    public $msg,$po_pajak;
    public function render()
    {
        return view('livewire.purchase-order.setting');
    }

    public function mount()
    {
        $this->po_pajak = get_setting('po_pajak');
    }

    public function save()
    {
        update_setting('po_pajak',$this->po_pajak);

        $this->msg = 'Data berhasil disimpan';
    }
}
