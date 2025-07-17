<?php

namespace App\Http\Livewire\Reseller;

use App\Models\Product;
use App\Models\ProductUom;
use App\Models\ResellerProduct;
use Livewire\Component;

class AddProduct extends Component
{
    public $products = [],$form = [
        'stock'=>0
    ],$product,$harga_beli,$product_uoms=[],$reseller_id;
    public function render()
    {
        return view('livewire.reseller.add-product');
    }

    public function mount($reseller_id)
    {
        $this->products = Product::orderBy('keterangan','ASC')->get();
        $this->product_uoms = ProductUom::get();
        $this->form['reseller_id'] = $reseller_id;
    }

    public function updated($propertyName)
    {
        if($propertyName=='form.product_id'){
            $this->product = Product::find($this->form['product_id']);
            $this->harga_beli = format_idr($this->product->harga_jual);
            $this->form['harga_beli'] = $this->product->harga_jual;
            $this->form['harga_jual'] = $this->product->harga_jual;
            $this->form['margin'] = 0;
            $this->form['product_uom_id'] = $this->product->product_uom_id;
            $this->form['sku'] = $this->product->kode_produksi;
            $this->form['nama'] = $this->product->keterangan;
        }

        if(in_array($propertyName,['form.harga_jual','form.harga_beli'])){
            if(is_numeric($this->form['harga_jual']) and is_numeric($this->form['harga_beli']))
                $this->form['margin'] = $this->form['harga_jual'] - $this->form['harga_beli'];
            else
                $this->form['margin'] = 0;
        }
    }

    public function save()
    {
        $this->validate([
            'form.product_id'=>'required',
            'form.harga_jual'=>'required'
        ]);

        ResellerProduct::create($this->form);

    }
}
