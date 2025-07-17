<?php

namespace App\Http\Livewire\PurchaseOrderSupplier;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\SupplierProduct;

class Insert extends Component
{
    public $pembelian = [],$no_po,$id_supplier,$supplier=[],$suppliers=[],$product_supplier=[],$product_po=[];
    public $data_product = [],$price,$qty,$product_uom_id,$product_id,$tab_active='tab-supplier';
    public function render()
    {
        return view('livewire.purchase-order.insert');
    }

    public function mount()
    {
        $this->no_po = "PO/".date('ym')."/".str_pad((PurchaseOrder::count()+1),4, '0', STR_PAD_LEFT);
        $this->suppliers = Supplier::orderBy('id','DESC')->get(); 
        $this->data_product = Product::select('id',\DB::raw("CONCAT(kode_produksi,' / ', keterangan) as text"))->get()->toArray();
    }

    public function updated($propertyName)
    {
        if($this->id_supplier){
            $this->product_supplier = SupplierProduct::where('id_supplier', $this->id_supplier)->orderBy('id','DESC')->get();
            $this->supplier = Supplier::find($this->id_supplier);
        }
    }

    public function addProduct()
    {
        $this->validate([
            'id_supplier' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
            'price' => 'required'
        ],[
            'id_supplier.required' => 'Supplier harus dipilih',
            'product_id.required' => 'Produk harus dipilih',
            'qty.required' => 'Produk harus diisi',
            'price.required' => 'Harga Produk harus diisi'
        ]);

        $this->product_po[] = [
            'id' => $this->product_id,
            'barcode' => '',
            'keterangan' => '',
            'qty' => $this->qty,
            'price' => $this->price
        ];
    }
}
