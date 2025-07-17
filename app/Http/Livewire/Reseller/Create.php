<?php

namespace App\Http\Livewire\Reseller;

use App\Models\Reseller;
use Livewire\Component;

class Create extends Component
{
    public $form = [
        'nama','alamat','pic_nama','pic_phone'
    ];

    public function render()
    {
        return view('livewire.reseller.create');
    }

    public function save()
    {
        $this->validate([
            'form.nama'=>'required',
            'form.alamat'=>'required',
            'form.pic_nama'=>'required',
            'form.pic_phone'=>'required',
        ]);
        
        $data = Reseller::create($this->form);

        session()->flash('message-success','Data berhasil disimpan.');

        return redirect()->route('reseller.edit',['data'=>$data->id]);
    }
}
