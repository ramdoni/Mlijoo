<?php

namespace App\Http\Livewire\UserSimpanan;

use Livewire\Component;
use App\Models\UserMember;

class Index extends Component
{
    public $keyword,$checkbox=false,$check_id=[],$simpanan_pokok=0,$simpanan_wajib=0,$simpanan_sukarela=0,$simpanan_lain_lain=0;
    public $jenis_simpanan_id;
    public $check_all=0;
    public function render()
    {
        return view('livewire.user-simpanan.index')->with(['anggota'=>$this->getData()->get()]);
    }

    public function mount()
    {
        $this->simpanan_pokok = $this->getData()->sum('simpanan_pokok');
        $this->simpanan_wajib = $this->getData()->sum('simpanan_wajib');
        $this->simpanan_sukarela = $this->getData()->sum('simpanan_sukarela');
        $this->simpanan_lain_lain = $this->getData()->sum('simpanan_lain_lain');
    }

    public function getData()
    {
        $anggota = UserMember::with(['simpananWajib'])->orderBy('name','ASC');
        if($this->keyword){
            $anggota->where(function($table){
                $table->where('name','LIKE',"%{$this->keyword}%")->orWhere('no_anggota_platinum','LIKE',"%{$this->keyword}%");
            });
        }

        return $anggota;
    }

    public function updated($propertyName)
    {
    }

    public function check_all_()
    {
        if($this->check_all==1){
            foreach($this->getData()->get() as $k => $item){
                if($item->status==1 and $item->payment_date=="") $this->check_id[$k] = $item->id;
            }
        }else{
            $this->check_id = [];
        }
    }

    public function assignCheckId()
    {
        $this->emit('checkId',$this->check_id);
    }
}
