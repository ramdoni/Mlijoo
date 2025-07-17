<?php

namespace App\Http\Livewire\Reseller;

use App\Models\ResellerProduct;
use Livewire\Component;
use Livewire\WithPagination;

class Product extends Component
{
    use WithPagination;
    public $reseller_id;
    public function render()
    {
        $data = ResellerProduct::where('reseller_id',$this->reseller_id)->orderBy('id','DESC');

        return view('livewire.reseller.product')->with(['data'=>$data->paginate(100)]);
    }

    public function mount($id)
    {
        $this->reseller_id = $id;
    }
}
