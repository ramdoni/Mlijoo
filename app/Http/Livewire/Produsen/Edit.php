<?php

namespace App\Http\Livewire\Produsen;

use Livewire\Component;
use App\Models\Produsen;
class Edit extends Component
{
    public $data,$form=[];
    public function render()
    {
        return view('livewire.produsen.edit');
    }
    
    public function mount(Produsen $data)
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
