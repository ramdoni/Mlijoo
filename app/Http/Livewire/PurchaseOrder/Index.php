<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $keyword,$filter=[],$total_po=0,$total_lunas=0,$total_belum_lunas=0;
    protected $listeners = ['refresh'=>'$refresh'];
    public function render()
    {
        $data = $this->getData();

        return view('livewire.purchase-order.index')->with(['data'=>$data->paginate(200)]);
    }

    public function mount()
    {
        $this->total_po = PurchaseOrder::whereYear('created_at',date('Y'))->sum('total_pembayaran');
        $this->total_belum_lunas = PurchaseOrder::whereYear('created_at',date('Y'))->where('status_invoice',0)->sum('total_pembayaran');
        $this->total_lunas = PurchaseOrder::whereYear('created_at',date('Y'))->where('status_invoice',1)->sum('total_pembayaran');

        \LogActivity::add('Purchase Order');
    } 

    public function getData()
    {
        $data = PurchaseOrder::orderBy('id','DESC');
        
        return $data;
    }

    public function insert()
    {
        $data = PurchaseOrder::create([
            'no_po'=> "PO/".date('ymd')."/".str_pad((PurchaseOrder::count()+1),4, '0', STR_PAD_LEFT),
            'status'=>0,
            'purchase_order_date'=>date('Y-m-d')
        ]);

        \LogActivity::add('Purchase Order Insert');

        return redirect()->route('purchase-order.detail',$data->id);
    }
}
