<?php

namespace App\Http\Livewire\Ecatalog;

use Livewire\Component;
use App\Models\Supplier\Product;
use Livewire\WithPagination;
use App\Models\Supplier\User;
class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $filter = [],$total_supplier=0,$total_product=0;
    public function render()
    {
        $data = Product::orderBy('id','DESC');

        foreach($this->filter as $field => $value){
            if($value=="") continue;
            if($field =='keterangan'){
                $data->where(function($table) use($value){
                    $table->where('keterangan','LIKE',"%{$value}%")
                    ->orWhere('kode_produksi','LIKE',"%{$value}%");
                });
            }else{
                $data->where($field,$value);
            }
        }

        return view('livewire.ecatalog.index')->with(['data'=>$data->paginate(100)]);
    }

    public function mount()
    {
        $this->total_supplier = 0;//User::where('user_access_id',7)->count();
        $this->total_product = 0;//Product::count();
    }

    public function clearFilter()
    {
        $this->filter = [];
    }
}
