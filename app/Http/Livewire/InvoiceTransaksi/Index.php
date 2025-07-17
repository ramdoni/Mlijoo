<?php

namespace App\Http\Livewire\InvoiceTransaksi;

use Livewire\Component;
use App\Models\InvoiceTransaksi;

class Index extends Component
{
    public $total_invoice=0,$total_invoice_qty=0,$total_invoice_lunas=0,$total_invoice_belum_lunas=0;
    public function render()
    {
        $data = $this->get_data();

        return view('livewire.invoice-transaksi.index')->with(['data'=>$data->paginate(250)]);
    }

    public function mount()
    {
        $this->total_invoice = InvoiceTransaksi::whereYear('created_at',date('Y'))->sum('amount');
        $this->total_invoice_qty = InvoiceTransaksi::whereYear('created_at',date('Y'))->count();
        $this->total_invoice_lunas = InvoiceTransaksi::where('status',1)->whereYear('created_at',date('Y'))->sum('amount');
        $this->total_invoice_belum_lunas = InvoiceTransaksi::where('status',0)->whereYear('created_at',date('Y'))->sum('amount');
    }

    public function get_data()
    {
        $data = InvoiceTransaksi::orderBy('id','DESC');

        return $data;
    }
}
