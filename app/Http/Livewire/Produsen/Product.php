<?php

namespace App\Http\Livewire\Produsen;

use App\Models\ProdusenProduct;
use Livewire\Component;
use Livewire\WithPagination;

class Product extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $total_perpage=200,$produsen_id;

    public function render()
    {
        $data = ProdusenProduct::where('produsen_id',$this->produsen_id)->orderBy('id','DESC');

        return view('livewire.produsen.product')->with(['data'=>$data->paginate($this->total_perpage)]);
    }

    public function mount($id)
    {
        $this->produsen_id = $id;
    }
}
