<?php

namespace App\Http\Livewire\PurchaseOrderSupplier;

use Livewire\Component;
use App\Models\PurchaseOrder;
use Livewire\WithPagination;
use Auth;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $keyword,$filter=[],$total_po=0,$total_lunas=0,$total_belum_lunas=0;
    protected $listeners = ['refresh'=>'$refresh'];
    public function render()
    {
        $data = $this->getData();

        return view('livewire.purchase-order-supplier.index')->with(['data'=>$data->paginate(200)]);
    }

    public function mount()
    {
        $this->total_po = PurchaseOrder::whereMonth('created_at',date('m'))->sum('total_pembayaran');
        $this->total_belum_lunas = PurchaseOrder::whereMonth('created_at',date('m'))->where('status_invoice',0)->sum('total_pembayaran');
        $this->total_lunas = PurchaseOrder::whereMonth('created_at',date('m'))->where('status_invoice',1)->sum('total_pembayaran');
    }

    public function getData()
    {
        $user = Auth::user();
        // dd($user);
        $data = PurchaseOrder::where('id_supplier', $user->id)->where('status', '<>', '0')->orderBy('id','DESC');
        
        return $data;
    }

    public function insert()
    {
        $data = new PurchaseOrder();
        $data->no_po = "PO/".date('ymd')."/".str_pad((PurchaseOrder::count()+1),4, '0', STR_PAD_LEFT);
        $data->status = 0;
        $data->save();

        return redirect()->route('purchase-order.detail',$data->id);
    }
}
