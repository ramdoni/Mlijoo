<?php

namespace App\Http\Livewire\UserSupplier;

use Livewire\Component;
use App\Models\UserMember;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Product;
use Livewire\WithPagination;

class ListProduk extends Component
{
    public $supplier_id,$insert=false,$insert_product=false,$data_product=[],$data;
    protected $listeners = ['reload'=>'$refresh'];
    public $product_id,$qty,$desc_product,$price,$product_uom_id;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = SupplierProduct::where('id_supplier', $this->data->id)->orderBy('id','DESC');

        return view('livewire.user-supplier.list-produk')->with(['products'=>$data->paginate(200)]);
    }

    public function updated($propertyName)
    {
        if($this->insert_product==true){
            $this->emit('insert-product');
        }
    }

    public function saveProduct()
    {
        $this->validate([
            'product_id' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'product_uom_id' => 'required'
        ],[
            'product_id.required' => 'Produk harus diisi',
            'qty.required' => 'QTY Produk harus diisi',
            'price.required' => 'Harga jual harus diisi',
        ]);

        $data = new SupplierProduct();
        $data->product_id = $this->product_id;
        $data->desc_product = $this->desc_product;
        $data->qty = $this->qty;
        $data->price = $this->price;
        $data->product_uom_id = $this->product_uom_id;
        $data->id_supplier = $this->data->id;
        $data->save();

        $this->insert_product = false;$this->reset('product_id','desc_product','qty','price','product_uom_id');
        $this->emit('message-success','Data produk berhasil ditambahkan');
        $this->emit('reload');
    }

    public function mount(Supplier $data)
    {
        $this->data = $data;
        $this->data_product = Product::select('id',\DB::raw("CONCAT(kode_produksi,' / ', keterangan) as text"))->get()->toArray();
    }

    public function save()
    {
        $this->reset(['nama_supplier','email','no_telp','alamat_supplier']);
        $this->insert = false;
    }
}
