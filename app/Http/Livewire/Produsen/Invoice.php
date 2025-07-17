<?php

namespace App\Http\Livewire\Produsen;

use App\Models\ProdusenInvoice;
use Livewire\Component;
use Livewire\WithPagination;
class Invoice extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $produsen_id,$total_perpage=100;
    public function render()
    {
        $data = ProdusenInvoice::where('produsen_id',$this->produsen_id)->orderBy('id','DESC');

        return view('livewire.produsen.invoice')->with(['data'=>$data->paginate($this->total_perpage)]);
    }

    public function mount($id)
    {
        $this->produsen_id = $id;
    }
}
