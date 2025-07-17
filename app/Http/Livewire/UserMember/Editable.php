<?php

namespace App\Http\Livewire\UserMember;

use Livewire\Component;
use App\Models\UserMember;
use App\Models\User;

class Editable extends Component
{
    public $data,$field,$is_edit=false,$value;
    public function render()
    {
        return view('livewire.user-member.editable');
    }

    public function mount($field,$data,$id)
    {
        $this->field = $field;
        $this->value = $data;
        $this->data = $id;
    }

    public function save()
    {
        $field = $this->field;
        $data = UserMember::find($this->data);

        // Sinkron Coopzone
        // $response = sinkronCoopzone([
        //     'url'=>'koperasi/user/edit',
        //     'field'=>$field,
        //     'value'=>$this->value,
        //     'no_anggota'=>$data->no_anggota_platinum
        // ]);

        // Sinkron Coopzone
        \App\Jobs\SyncCoopzone::dispatch([
                'url'=>'koperasi/user/edit',
                'no_anggota'=>$data->no_anggota_platinum,
                'field'=>$field,
                'value'=>$this->value
            ]);

        $data->$field = $this->value;
        $data->save();

        // update no anggota
        $user  = User::find($data->user_id);
        if($user){
            $user->username = $data->no_anggota_platinum;
            $user->save();
        }

        $this->is_edit = false;
    }
}
