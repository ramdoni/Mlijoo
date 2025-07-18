<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\TransaksiItem;
use App\Models\Supplier;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    public $data,$penjualan,$pembelian,$is_ppn,$harga,$harga_jual,$diskon,$ppn=0,$harga_produksi=0,$margin=0,$data_supplier=[],$file;
    protected $listeners = ['reload-page'=>'$refresh'];
    public function render()
    {
        return view('livewire.product.edit');
    }

    public function mount(Product $data)
    {
        \LogActivity::add("Product Detail {$data->id}");

        $this->data = $data;
        $this->penjualan = TransaksiItem::where('product_id',$data->id)->get();
        if($this->data->ppn==0) {
            $this->data->ppn = @$this->data->harga_jual * 0.11;
            $this->data->save();
        }
        $this->pembelian = ProductStock::where('product_id',$this->data->id)->get();
        $this->is_ppn = $this->data->is_ppn;
        $this->harga = $this->data->harga;
        $this->harga_jual = $this->data->harga_jual;
        $this->diskon = $this->data->diskon;

        if($this->data->ppn!=0) $this->is_ppn = 1;
        if($this->is_ppn==1 and $this->harga){
            $this->ppn = $this->harga * 0.11;
        }

        // Harga Produksi
        if($this->harga>0) $this->harga_produksi = $this->harga + $this->ppn;
        // Margin
        if($this->harga_jual>0 && $this->harga_produksi>0) $this->margin = $this->harga_jual  - $this->harga_produksi; 
        if($this->diskon>0 and $this->margin>0) $this->margin = $this->margin - $this->diskon;
        $this->data_supplier = Supplier::select('supplier.*')->join('supplier_product','supplier_product.id_supplier','=','supplier.id')->where('supplier_product.product_id',$this->data->id)->groupBy('supplier.id')->get();
    }

    public function updated($propertyName)
    {
        if($this->is_ppn==1 and $this->harga){
            $this->ppn = $this->harga * 0.11;
        }else{
            $this->ppn = 0;
        }
        // Harga Produksi
        if($this->harga>0) $this->harga_produksi = $this->harga + $this->ppn;
        // Margin
        if($this->harga_jual>0 && $this->harga_produksi>0) $this->margin = $this->harga_jual  - $this->harga_produksi; 
        if($this->diskon>0 and $this->margin>0) $this->margin = $this->margin - $this->diskon;
    }

    public function update()
    {
        $validate['harga_jual'] = 'required';
        if($this->file){
            $validate['file'] = 'image:max:1024';
        }

        $this->validate($validate);

        $this->data->is_ppn  = $this->is_ppn;
        $this->data->ppn = $this->ppn;
        $this->data->harga_jual = $this->harga_jual;
        $this->data->harga = $this->harga;
        $this->data->margin = $this->margin;
        $this->data->diskon = $this->diskon;
        $this->data->save();

        if($this->file){
            $name = rand().'-'.date('Ymdhis').'.'.$this->file->extension();
            $this->file->storePubliclyAs('product/'.$this->data->id,$name,'public');
            $this->data->update(['image'=>'storage/product/'.$this->data->id.'/'.$name]);
        }
        
        $param['type'] = 'product';
        $param['products'] = $this->data;

        // \App\Jobs\JobKoperasi::dispatch($param);

        \LogActivity::add("Product Update {$this->data->id}");

        $this->emit('message-success','Data berhasil disimpan.');
    }
}