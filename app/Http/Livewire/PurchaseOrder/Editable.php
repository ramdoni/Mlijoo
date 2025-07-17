<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrderDetail;

class Editable extends Component
{
    public $data,$field,$is_edit=false,$value;
    public function render()
    {
        return view('livewire.purchase-order.editable');
    }

    public function mount($field,$data,$id)
    {
        $this->field = $field;
        $this->value = $data;
        $this->data = $id;
    }

    public function save()
    {
        $field = $this->field;
        $data = PurchaseOrderDetail::find($this->data);
        $data->$field = $this->value;
        $data->save();

        $this->is_edit = false;
        $this->emit('reload');
    }
}
