<?php

namespace App\Http\Livewire\ProdusenInvoice;

use App\Models\ProdusenInvoice;
use Livewire\Component;

class Index extends Component
{
    public $total_invoice=0,$total_invoice_lunas=0,$total_invoice_belum_lunas=0,$total_invoice_qty=0;
    public function render()
    {
        $data = ProdusenInvoice::orderBy('id','DESC'); 

        return view('livewire.produsen-invoice.index')->with(['data'=>$data->paginate(100)]);
    }
}
