<?php

namespace App\Http\Livewire\Reseller;

use App\Models\Reseller;
use Livewire\Component;

class Edit extends Component
{
    public $data,$form=[];
    public function render()
    {
        return view('livewire.reseller.edit');
    }

    public function mount(Reseller $data)
    {
        $this->data = $data;
        $this->form = $data->toArray();
    }

    public function update()
    {
        $this->data->update($this->form);

        $this->emit('message-success','Data berhasil disimpan');
    }
}
