<?php

namespace App\Http\Livewire\UserSimpanan;

use Livewire\Component;
use App\Models\UserMember;
use App\Models\UserMemberSimpananWajib;
use App\Models\Simpanan;

class Insert extends Component
{
    public $check_id=[],$jenis_simpanan_id,$bulan=1,$amount;
    protected $listeners = ['checkId'=>'checkId'];
    public function render()
    {
        return view('livewire.user-simpanan.insert');
    }

    public function checkId($data)
    {
        $this->check_id = $data;
    }

    public function save()
    {
        $validate['jenis_simpanan_id'] = 'required';

        if($this->jenis_simpanan_id==3) $validate['amount'] = 'required';

        $this->validate($validate);
        
        /**
         * Simpanan Wajib
         */
        if($this->jenis_simpanan_id==2){
            $bulan = [0=>'januari',1=>'februari',2=>'maret',3=>'april',4=>'mei',5=>'juni',6=>'juli',7=>'agustus',8=>'september',9=>'oktober',10=>'november',11=>'desember'];
            foreach(UserMember::whereIn('id',$this->check_id)->get() as $item){
                $find = UserMemberSimpananWajib::where(['user_member_id'=>$item->id,'tahun'=>date('Y')])->first();
                if(!$find){
                    $find = new UserMemberSimpananWajib();
                    $find->user_member_id = $item->id;
                    $find->tahun = date('Y');
                    
                    for($i=0;$i<$this->bulan;$i++){
                        if(isset($bulan[$i])) {
                            $m_name = $bulan[$i];
                            $find->$m_name = get_setting('simpanan_wajib');
                            
                            /**
                             * Update Simpanan
                             */
                            $find_member = UserMember::find($item->id);
                            $find_member->simpanan_wajib = $find_member->simpanan_wajib+get_setting('simpanan_wajib');
                            $find_member->save();

                            Simpanan::create(['user_member_id'=>$item->id,'payment_date'=>date('Y-m-d'),'status'=>1,'jenis_simpanan_id'=>2,'amount'=>get_setting('simpanan_wajib')]);
                        }
                    }
                    $find->save();

                }else{
                    $num=0;
                    foreach($bulan as $k => $month_name){
                        if($num>=$this->bulan) continue;
                        if(!$find->$month_name){
                            $find->$month_name = get_setting('simpanan_wajib');
                            $num++;
                            
                            /**
                             * Update Simpanan
                             */
                            $find_member = UserMember::find($item->id);
                            $find_member->simpanan_wajib = $find_member->simpanan_wajib+get_setting('simpanan_wajib');
                            $find_member->save();

                            Simpanan::create(['user_member_id'=>$item->id,'payment_date'=>date('Y-m-d'),'status'=>1,'jenis_simpanan_id'=>2,'amount'=>get_setting('simpanan_wajib')]);
                        }
                    }
                    $find->save();
                }
            }
        }

        /**
         * Simpanan Sukarela
         */
        if($this->jenis_simpanan_id==2){

        }

        session()->flash('message-success',__('Data Simpanan berhasil di submit'));

        return redirect()->route('user-simpanan.index');
    }
}
