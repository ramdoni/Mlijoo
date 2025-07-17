<?php

namespace App\Http\Livewire\UserMember;

use Livewire\Component;
use App\Models\UserMember;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class Upload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.user-member.upload');
    }

    public function save()
    {
        ini_set('memory_limit', '-1');
        $this->validate([
            'file'=>'required|mimes:xls,xlsx|max:51200' // 50MB maksimal
        ]);
        
        $path = $this->file->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $data = $reader->load($path);
        $sheetData = $data->getActiveSheet()->toArray(null, true, false, true);

        if(count($sheetData) > 0){
            $countLimit = 1;
            foreach($sheetData as $key => $i){
                if($key<=0 || $i['A']=="") continue; // skip header
            
                $no_anggota = str_pad(($i['B']),5, '0', STR_PAD_LEFT);
                $nama = $i['C'];
                $member = UserMember::where('no_anggota_platinum',$no_anggota)->first();

                if(!$member){
                    $user = new User();
                    $user->user_access_id = 4; // Member
                    $user->nik = $no_anggota;
                    $user->name = $nama;
                    $user->password = Hash::make('12345678');
                    $user->username = $no_anggota;
                    $user->save();
                    
                    $member = new UserMember();
                    $member->user_id = $user->id;
                }

                $member->name = $nama;
                $member->no_anggota_platinum = $no_anggota;
                $member->plafond = 2000000;
                $member->save();
            }
        }

        session()->flash('message-success',__('Data berhasil di upload'));

        return redirect()->route('user-member.index');
    }
}