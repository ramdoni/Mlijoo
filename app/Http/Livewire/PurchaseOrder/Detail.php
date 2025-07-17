<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\SupplierProduct;
use App\Models\InvoicePoItem;
use App\Models\Produsen;
use Livewire\WithFileUploads;

class Detail extends Component
{
    use WithFileUploads;
    
    public $data,$pembelian = [],$no_po,$id_supplier,$product_supplier=[],$product_po=[];
    public $data_product = [],$price,$qty,$product_uom_id,$product_id,$tab_active='tab-supplier',$biaya_pengiriman=0,$total_pembayaran=0;
    public $alamat_penagihan,$purchase_order_date,$delivery_order_number,$delivery_order_date,$disc=0,$pajak=0,$catatan;
    protected $listeners = ['reload'=>'$refresh'];
    public $nama_product, $list_product_supplier,$type_pajak=1;
    public $payment_date, $payment_amount, $file_bukti_pembayaran, $metode_pembayaran;
    public $data_invoice, $sisa_bayar_inv,$produsens=[],$form=[];
    public function render()
    {
        
        if($this->id_supplier){
            $this->list_product_supplier = SupplierProduct::where('produsen_id', $this->produsen_id)->orderBy('id','DESC')->get();
        }else{
            $this->list_product_supplier = [];
        }

        return view('livewire.purchase-order.detail');
    }

    public function mount(PurchaseOrder $data)
    {
        $this->data = $data;
        $this->no_po = $data->no_po;
        $this->catatan = $data->catatan;
        $this->pajak = $data->ppn;
        $this->biaya_pengiriman = $data->biaya_pengiriman;
        $this->alamat_penagihan = $data->alamat_penagihan?$data->alamat_penagihan:get_setting('address');
        $this->purchase_order_date = $data->purchase_order_date;
        $this->delivery_order_number = $data->delivery_order_number;
        $this->delivery_order_date = $data->delivery_order_date;
        $this->produsens = Produsen::orderBy('nama','ASC')->get();

        $data_product = [];
        foreach(Product::get() as $k => $item){
            $data_product[$k]['id'] = $item->id;
            $data_product[$k]['text'] = $item->kode_produksi;
            $data_product[$k]['text'] .= $item->kode_produksi ? ' / '.$item->keterangan : $item->keterangan;
        }
        $this->data_product = $data_product;// Product::select('id',\DB::raw("CONCAT(kode_produksi,' - ', keterangan) as text"))->get()->toArray();
        
        $this->form = $data->toArray();


        // $list_product_supplier = []; 
        foreach(SupplierProduct::where('id_supplier', $this->id_supplier)->orderBy('id','DESC')->get() as $k => $item){
            $list_product_supplier['id'] = $item->id;
            $list_product_supplier['text'] = $item->barcode;
            $list_product_supplier['text'] .= $item->barcode ? ' / '.$item->nama_product : $item->nama_product;

            array_push($this->data_product, $list_product_supplier);
        }
       
        $this->data_invoice = InvoicePoItem::where('po_id', $data->id)->get();
        $this->sisa_bayar_inv = $data->total_pembayaran - \App\Models\InvoicePoItem::where('po_id', $this->data->id)->sum('amount');
        $this->payment_amount = $this->sisa_bayar_inv;
        $this->type_pajak = $data->type_pajak;

        if($this->type_pajak==1){
            $this->pajak =  floor((get_setting('po_pajak') / 100) * $data->total_pembayaran);
        }
    }

    public function cancel()
    {
        \LogActivity::add("Purchase Order - Cancel [{$this->data->no_po}]");

        $this->data->status = 6; // cancel
        $this->data->save();
        
        session()->flash('message-success',"Purchase order canceled");

        return redirect()->route('purchase-order.detail',$this->data->id);
    }

    public function revision()
    {
        \LogActivity::add("Purchase Order - Revision [{$this->data->no_po}]");
        
        if($this->data->revision_running_number=="") {
            $this->data->revision_running_number = 1;
            $this->data->no_po = $this->data->no_po ."-R1";
        }else{
            $running_number = $this->data->revision_running_number + 1;
            $this->data->no_po = str_replace("-R{$this->data->revision_running_number}","-R{$running_number}",$this->data->no_po);
            $this->data->revision_running_number = $running_number;
        }   

        $this->data->status = 0; // Revision
        $this->data->save();

        session()->flash('message-success',"Purchase order Revision to {$this->data->no_po}");

        return redirect()->route('purchase-order.detail',$this->data->id);
    }

    public function updated($propertyName)
    {
        $this->total_pembayaran = 0;
        foreach($this->data->details as $item){
            $this->total_pembayaran += $item->price * $item->qty;
        }

        if($this->type_pajak==1){
            $this->pajak =  ((get_setting('po_pajak') / 100) * $this->total_pembayaran);
        }

        if($propertyName=='id_supplier'){
            $this->data->id_supplier = $this->id_supplier;
            $this->data->save();
        }

        if($this->id_supplier){
            $this->product_supplier = $this->list_product_supplier = SupplierProduct::where('id_supplier', $this->id_supplier)->orderBy('id','DESC')->get();
            $this->supplier = Supplier::find($this->id_supplier);
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
            // 'id_supplier' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
            'price' => 'required'
        ],[
            // 'id_supplier.required' => 'Supplier harus dipilih',
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
        $this->price = 0;$this->disc = 0;$this->qty = 0;
        $this->reset('product_uom_id','product_id');
        $this->emit('reload');
    }

    public function deleteProduct($id)
    {
        PurchaseOrderDetail::find($id)->delete();

        $this->emit('reload');
    }

    public function save()
    {
        \LogActivity::add("Purchase Order - Save [{$this->data->no_po}]");

        $this->total_pembayaran = 0;$total_qty = 0;$total_product = 0;
        foreach($this->data->details as $item){
            $this->total_pembayaran += ($item->price - ($item->disc?$item->disc:0)) * $item->qty;
            $total_qty += $item->qty;$total_product++; 
        }
        
        $this->data->total_qty = $total_qty;
        $this->data->total_product = $total_product;
        $this->data->ppn = $this->pajak;
        $this->data->biaya_pengiriman = $this->biaya_pengiriman;
        $this->data->total_pembayaran = $this->total_pembayaran + $this->biaya_pengiriman + $this->pajak;
        $this->data->alamat_penagihan = $this->alamat_penagihan;
        $this->data->purchase_order_date = $this->purchase_order_date;
        $this->data->delivery_order_number = $this->delivery_order_number;
        $this->data->delivery_order_date = $this->delivery_order_date;
        $this->data->catatan = $this->catatan;
        $this->data->id_supplier = $this->id_supplier;
        $this->data->type_pajak = $this->type_pajak;
        $this->data->save();
        $this->data->update($this->form);
    }

    public function sendpayment()
    {
        $validate = [
            'payment_date' => 'required',
            'metode_pembayaran' => 'required'
        ];

        if($this->file_bukti_pembayaran) $validate['file_bukti_pembayaran'] = 'file|mimes:xlsx,csv,xls,doc,docx,pdf,jpg,jpeg,png|max:51200'; //] 50MB Max
        
        $this->validate($validate);

        if($this->data->status == 2){
            if(\App\Models\InvoicePoItem::where('po_id', $this->data->id)->get()){
                $sisa_bayar = $this->data->total_pembayaran - \App\Models\InvoicePoItem::where('po_id', $this->data->id)->sum('amount');
                $sisa_bayar = $sisa_bayar - $this->payment_amount;
                if($sisa_bayar == 0){
                    $this->data->status = 3;
                    $this->data->save();
                }
            }else{
                $sisa_bayar = $this->data->total_pembayaran - $this->payment_amount;
                if($sisa_bayar == 0){
                    $this->data->status = 3;
                    $this->data->save();
                }
            }
        }

        $payinvoice                         = new InvoicePoItem();
        $payinvoice->po_id                 = $this->data->id;
        $payinvoice->amount                 = $this->payment_amount;
        $payinvoice->metode_pembayaran      = $this->metode_pembayaran;
        if($this->file_bukti_pembayaran!="") {
            $name = $this->data->id.".".$this->file_bukti_pembayaran->extension();
            $this->file_bukti_pembayaran->storePubliclyAs("public/invoice-po-supplier/{$this->data->id}", $name);
            $payinvoice->file = "storage/invoice-po-supplier/{$this->data->id}/{$name}";
        }
        $payinvoice->created_at             = date('Y-m-d H:i:s');
        $payinvoice->updated_at             = date('Y-m-d H:i:s');
        $payinvoice->save();

        session()->flash('message-success',"Pembayaran dikirimkan ke Supplier");

        return redirect()->route('purchase-order.detail',$this->data->id);
    }

    public function saveAsDraft()
    {
        $this->save();

        session()->flash('message-success',"Purchase Order berhasil di simpan");

        \LogActivity::add('Purchase Order Save as Draft #'. $this->data->id);

        return redirect()->route('purchase-order.detail',$this->data->id);
    }

    public function submit()
    {
        $this->save();
        
        \LogActivity::add('Purchase Order Issued #'. $this->data->id);
        
        $this->data->submitted_date = date('Y-m-d');
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
        }

        session()->flash('message-success',"Purchase Order berhasil di submit");

        return redirect()->route('purchase-order.detail',$this->data->id);
    }
}