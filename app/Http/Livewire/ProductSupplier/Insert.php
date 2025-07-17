<?php

namespace App\Http\Livewire\ProductSupplier;

use Livewire\Component;
use App\Models\SupplierProduct;
use Auth;

class Insert extends Component
{
    public $barcode,$desc_product,$nama_product, $qty, $price;
    public function render()
    {
        return view('livewire.product-supplier.insert');
    }

    public function save()
    {
        $this->validate([
            'barcode' => 'required',
            'desc_product' => 'required',
            'nama_product' => 'required'
        ]);

        $user               = Auth::user();
        $data               = new SupplierProduct();
        $data->id_supplier  = $user->id;
        $data->barcode      = $this->barcode;
        $data->desc_product = $this->desc_product;
        $data->nama_product = $this->nama_product;
        $data->price        = $this->price;
        $data->qty          = $this->qty;
        $data->save();

        session()->flash('message-success',"Product berhasil di simpan, selanjutnya kamu harus tentukan harga jual produk");

        return redirect()->route('product-supplier.detail',$data->id);
    }
}
