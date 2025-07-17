<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithFileUploads;

class Insert extends Component
{
    use WithFileUploads;

    public $kode_produksi,$keterangan,$type="Stock",$file;
    
    public function render()
    {
        return view('livewire.product.insert');
    }

    public function save()
    {
        $this->validate([
            'kode_produksi' => 'required',
            'keterangan' => 'required',
            'type' => 'required',
            'file' => 'image:max:1024',
        ]);

        $data = new Product();
        $data->kode_produksi = $this->kode_produksi;
        $data->keterangan = $this->keterangan;
        $data->type = $this->type;
        $data->save();

        $name = rand().'-'.date('Ymdhis').'.'.$this->file->extension();
        $this->file->storePubliclyAs('product/'.$data->id,$name,'public');

        $data->update(['image'=>'storage/product/'.$data->id.'/'.$name]);

        session()->flash('message-success',"Product berhasil di simpan, selanjutnya kamu harus tentukan harga jual produk");

        return redirect()->route('product.detail',$data->id);
    }
}
