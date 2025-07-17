<?php

namespace App\Http\Livewire\Reseller;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Reseller;

class Index extends Component
{
    use WithPagination;
    public $totals = [
        'total_reseller'=>0,
        'transaksi_hari_ini'=>0,
        'penjualan_bulan_ini'=>0,
        'transaksi_bulan_ini'=>0
    ];
    public $total_perpage = 100;
    public function render()
    {
        $data = Reseller::orderBy('id','DESC');

        return view('livewire.reseller.index')->with(['data'=>$data->paginate($this->total_perpage)]);
    }
}
