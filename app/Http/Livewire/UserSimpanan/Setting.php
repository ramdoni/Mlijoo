<?php

namespace App\Http\Livewire\UserSimpanan;

use Livewire\Component;

class Setting extends Component
{
    public $simpanan_pokok,$simpanan_wajib,$msg,$bunga_pertahun_simpanan_sukarela;
    public function render()
    {
        return view('livewire.user-simpanan.setting');
    }
    
    public function mount()
    {
        $this->simpanan_pokok = get_setting('simpanan_pokok');
        $this->simpanan_wajib = get_setting('simpanan_wajib');
        $this->bunga_pertahun_simpanan_sukarela = get_setting('bunga_pertahun_simpanan_sukarela');
    }

    public function save()
    {
        update_setting('simpanan_pokok',$this->simpanan_pokok);
        update_setting('simpanan_wajib',$this->simpanan_wajib);
        update_setting('bunga_pertahun_simpanan_sukarela',$this->bunga_pertahun_simpanan_sukarela);

        $this->msg = 'Data berhasil disimpan';
    }
}
