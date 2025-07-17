<?php

namespace App\Http\Livewire\Setting;

use Livewire\Component;

class Aplikasi extends Component
{
    public $apps_token,$apps_type,$apps_url,$apps_sinkron;
    public function render()
    {
        return view('livewire.setting.aplikasi');
    }

    public function mount()
    {
        $this->apps_token = get_setting('apps_token');
        $this->apps_type = get_setting('apps_type');
        $this->apps_url = get_setting('apps_url');
        $this->apps_sinkron = get_setting('apps_sinkron');
    }

    public function save()
    {
        update_setting('apps_token',$this->apps_token);
        update_setting('apps_type',$this->apps_type);
        update_setting('apps_url',$this->apps_url);
        update_setting('apps_sinkron',$this->apps_sinkron);

        $this->emit('message-success','Data berhasil disimpan');
    }
}
