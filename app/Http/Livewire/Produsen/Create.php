<?php

namespace App\Http\Livewire\Produsen;

use App\Models\Produsen;
use Livewire\Component;

class Create extends Component
{
    public $form=[
        'nama'=>'',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',                                    
        'alamat'=>'',
        'pic_nama'=>'',
        'pic_phone'=>'',
        'spesifikasi'=>'',
        'status'=>1,
        'status_pajak'=>1
    ];
    public function render()
    {
        return view('livewire.produsen.create');
    }

    public function save()
    {
        $this->validate([
            'form.nama'=>'required',
            'form.alamat'=>'required',
            'form.pic_nama'=>'required',
            'form.pic_phone'=>'required',
        ]);
        
        $data = Produsen::create($this->form);

        session()->flash('message-success','Data berhasil disimpan.');

        return redirect()->route('produsen.edit',['data'=>$data->id]);
    }
}
