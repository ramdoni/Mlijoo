<?php

namespace App\Http\Livewire\PurchaseOrderSupplier;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SupplierProduct;
use App\Models\InvoicePo;
use App\Models\InvoicePoItem;


class Detail extends Component
{
    public $data,$pembelian = [],$no_po,$id_supplier,$supplier=[],$suppliers=[],$product_supplier=[],$product_po=[];
    public $data_product = [],$price,$qty,$product_uom_id,$product_id,$tab_active='tab-supplier',$biaya_pengiriman=0,$total_pembayaran=0;
    public $alamat_penagihan,$purchase_order_date,$delivery_order_number,$delivery_order_date,$disc=0,$pajak=0,$catatan;
    protected $listeners = ['reload'=>'$refresh'];
    public $nama_product, $data_invoice;
    public function render()
    {
        return view('livewire.purchase-order-supplier.detail');
    }

    public function mount(PurchaseOrder $data)
    {
        $this->data = $data;
        $this->id_supplier = $data->id_supplier;
        if($this->id_supplier) $this->supplier = Supplier::find($this->id_supplier);
        $this->no_po = $data->no_po;
        $this->catatan = $data->catatan;
        $this->pajak = $data->ppn;
        $this->biaya_pengiriman = $data->biaya_pengiriman;
        $this->alamat_penagihan = $data->alamat_penagihan?$data->alamat_penagihan:get_setting('address');
        $this->purchase_order_date = $data->purchase_order_date;
        $this->delivery_order_number = $data->delivery_order_number;
        $this->delivery_order_date = $data->delivery_order_date;
        $this->suppliers = Supplier::orderBy('id','DESC')->get(); 
        $data_product = [];
        // foreach(SupplierProduct::get() as $k => $item){
        foreach(Product::get() as $k => $item){
            $data_product[$k]['id'] = $item->id;
            $data_product[$k]['text'] = $item->kode_produksi;
            $data_product[$k]['text'] .= $item->kode_produksi ? ' / '.$item->keterangan : $item->keterangan;
        }
        $this->data_product = $data_product;// Product::select('id',\DB::raw("CONCAT(kode_produksi,' - ', keterangan) as text"))->get()->toArray();

        $this->data_invoice = InvoicePoItem::where('po_id', $data->id)->get();
    }

    public function updated($propertyName)
    {
        if($propertyName=='id_supplier'){
            $this->data->id_supplier = $this->id_supplier;
            $this->data->save();
        }
        if($this->id_supplier){
            $this->product_supplier = SupplierProduct::where('id_supplier', $this->id_supplier)->orderBy('id','DESC')->get();
            $this->supplier = Supplier::find($this->id_supplier);
        }

        foreach($this->data->details as $item){
            $this->total_pembayaran += $item->price * $item->qty;
        }

        if($propertyName=='product_id'){
            $product = SupplierProduct::where(['id_supplier'=>$this->id_supplier,'product_id'=>$this->product_id])->first();
            if($product) $this->price = $product->price;
        }
    }

    public function addProductSupplier(SupplierProduct $data)
    {
        $detail = PurchaseOrderDetail::where(['id_po'=>$this->data->id,'product_id'=>$data->product_id])->first();
        if(!$detail){
            $detail = new PurchaseOrderDetail();
            $detail->qty = 1;
            $detail->id_po = $this->data->id;
            $detail->item = $this->nama_product;
            $detail->product_id = $data->product_id;
            $detail->product_uom_id = $data->product_uom_id;
            $detail->price = $data->price;
            $detail->save();
        }else{
            $detailupdate = PurchaseOrderDetail::where(['id_po'=>$this->data->id,'product_id'=>$data->product_id])->first();
            $detailupdate->qty = $detailupdate->qty+1;
            $detailupdate->total_price = $detailupdate->qty * $data->price;
            $detailupdate->save();
        }

        $this->emit('reload');
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

        $detail = PurchaseOrderDetail::where(['id_po'=>$this->data->id,'product_uom_id'=>$this->product_uom_id,'product_id'=>$this->product_id])->first();
        if(!$detail){
            $detail = new PurchaseOrderDetail();
            $detail->id_po = $this->data->id;
            $detail->product_id = $this->product_id;
            $detail->product_uom_id = $this->product_uom_id;
            $detail->qty = $this->qty;
            $detail->price = $this->price;
            $detail->disc = $this->disc;
            $detail->save();
        }else{
            $detail->qty = $detail->qty + $this->qty;
            $detail->save();
        }

        $this->emit('reload');
    }

    // public function deleteProduct($id)
    // {
    //     PurchaseOrderDetail::find($id)->delete();
    //     $this->emit('reload');
    // }

    // public function save()
    // {
    //     foreach($this->data->details as $item){
    //         $this->total_pembayaran += ($item->price - $item->disc) * $item->qty;
    //     }
        
    //     $this->data->ppn = $this->pajak;
    //     $this->data->biaya_pengiriman = $this->biaya_pengiriman;
    //     $this->data->total_pembayaran = $this->total_pembayaran + $this->biaya_pengiriman + $this->pajak;
    //     $this->data->alamat_penagihan = $this->alamat_penagihan;
    //     $this->data->purchase_order_date = $this->purchase_order_date;
    //     $this->data->delivery_order_number = $this->delivery_order_number;
    //     $this->data->delivery_order_date = $this->delivery_order_date;
    //     $this->data->catatan = $this->catatan;
    //     $this->data->save();
    // }

    public function sendinvoice(){
        $createinvoice                   = new InvoicePo();
        $createinvoice->no_invoice       = 'INV/'.$this->data->no_po;
        $createinvoice->amount           = $this->data->total_pembayaran;
        $createinvoice->created_at       = date('Y-m-d H:i:s');
        $createinvoice->updated_at       = date('Y-m-d H:i:s');
        $createinvoice->save();

        $this->data->status = 2;
        $this->data->save();

        


        session()->flash('message-success',"Invoice berhasil dikirimkan ke Customer");

        return redirect()->route('purchase-order-supplier.detail',$this->data->id);
    }

    public function updateaspaid(){
        $this->data->status = 4;
        $this->data->save();

        // dd($this->data->details);
        // foreach($this->data->details as $item){
        //     $cekproduk = Product::where('keterangan', $item->item)->first();
        //     // dd($cekproduk);
        //     if($cekproduk){
        //         $cekproduk->qty = isset($cekproduk->qty) ? $cekproduk->qty + $item->qty : $item->qty;
        //         $cekproduk->save();
        //         continue;
        //     }else{
        //         $insertproduk                   = new Product();
        //         $insertproduk->kode_produksi    = "";
        //         $insertproduk->keterangan       = $item->item;
        //         $insertproduk->harga            = $item->price;
        //         $insertproduk->qty              = $item->qty;
        //         $insertproduk->type             = "";
        //         $insertproduk->save();
        //         continue;
        //     }
        //     $this->total_pembayaran += $item->price * $item->qty;
        // }

        session()->flash('message-success',"Pembayaran Purchase Order dikonfirmasi");

        return redirect()->route('purchase-order-supplier.detail',$this->data->id);
    }

    public function approve($id){
        InvoicePoItem::where('id', $id)->update(['status'=> '1']);

        session()->flash('message-success',"Pembayaran Purchase Order dikonfirmasi");

        return redirect()->route('purchase-order-supplier.detail',$this->data->id);
    }

    public function saveAsDraft()
    {
        $this->save();

        session()->flash('message-success',"Purchase Order berhasil di simpan");

        return redirect()->route('purchase-order.detail',$this->data->id);
    }

    public function submit()
    {
        $this->save();

        $this->data->status = 1;
        $this->data->save();

        foreach($this->data->details as $item){
            $product_supplier = SupplierProduct::where(['id_supplier'=>$this->id_supplier,
                                                        'product_uom_id'=>$item->product_uom_id,
                                                        'product_id'=>$item->product_id
                                                        ])->first();
            if(!$product_supplier){
                $product_supplier = new SupplierProduct();
                $product_supplier->id_supplier = $this->id_supplier;
                $product_supplier->product_uom_id = $item->product_uom_id;
                $product_supplier->product_id = $item->product_id;
                $product_supplier->price = $item->price;
                $product_supplier->disc = $item->disc;
            }else{
                $product_supplier->product_uom_id = $item->product_uom_id;
                $product_supplier->product_id = $item->product_id;
                $product_supplier->price = $item->price;
                $product_supplier->disc = $item->disc;
            }
            $product_supplier->save();

            if(isset($item->product->id)){
                Product::find($item->product_id)->update(['qty'=> $item->qty + $item->product->qty]);
            }
        }

        session()->flash('message-success',"Purchase Order berhasil di submit");

        return redirect()->route('purchase-order.detail',$this->data->id);
    }
}
