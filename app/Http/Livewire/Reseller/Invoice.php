<?php

namespace App\Http\Livewire\Reseller;

use App\Models\ResellerInvoice;
use Livewire\Component;
use Livewire\WithPagination;

class Invoice extends Component
{
    use WithPagination;
    public $reseller_id;
    public function render()
    {
        $data = ResellerInvoice::where('reseller_id',$this->reseller_id)->orderBy('id','DESC');

        return view('livewire.reseller.invoice')->with(['data'=>$data->paginate(100)]);
    }

    public function mount($id)
    {
        $this->reseller_id = $id;
    }
}
