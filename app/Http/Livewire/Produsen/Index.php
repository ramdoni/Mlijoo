<?php

namespace App\Http\Livewire\Produsen;

use App\Models\Produsen;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $totals = ['penjualan_hari_ini'=>0,
                    'transaksi_hari_ini'=>0,
                    'penjualan_bulan_ini'=>0,
                    'transaksi_bulan_ini'=>0
                ];

    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = Produsen::orderBy('id','DESC');

        return view('livewire.produsen.index')->with(['data'=>$data->paginate(100)]);
    }
}
