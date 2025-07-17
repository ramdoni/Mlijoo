<?php

namespace App\Http\Livewire\Kasir;

use Livewire\Component;
use App\Models\Product;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\UserMember;
use App\Models\UserKasir;
use App\Jobs\SyncCoopzone;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{    
    public $data=[],$kode_produksi,$qty=1,$sub_total=0,$total=0,$msg_error="",$metode_pembayaran=4,$success=false;
    public $no_transaksi='',$transaksi,$jenis_transaksi=2,$msg_error_jenis_transaksi,$no_anggota,$anggota,$temp_anggota;
    public $status_transaksi=0,$uang_tunai,$total_kembali=0,$total_qty=0,$message_metode_pembayaran,$ppn,$total_and_ppn,$no_kartu_debit_kredit;
    public $user_kasir,$msg_error_anggota,$url_cetak_struk,$data_product=[],$data_anggota=[],$selected_item,$edit_stock;
    protected $listeners = ['event_bayar' => 'event_bayar',
                            'okeAnggota'=>'okeAnggota',
                            'deleteAnggota'=>'deleteAnggota',
                            'getProduct'=>'getProduct',
                            'setAnggota'=>'setAnggota',
                            'edit_item'=>'setSelectedItem'];
    public function render()
    {
        return view('livewire.kasir.index')->layout('layouts.kasir');
    }

    public function mount()
    {
        $this->user_kasir = UserKasir::where(['user_id'=>Auth::user()->id,'status'=>0])->whereDate('start_work_date',date('Y-m-d'))->first();
        if(!$this->user_kasir){
            $this->emit('show-start-work');
        }
        
        foreach(Product::get() as $k => $item){
            $this->data_product[$k]['id'] = $item->kode_produksi;
            $this->data_product[$k]['text'] = $item->kode_produksi .'/'. $item->keterangan;
        }
        foreach(UserMember::get() as $k => $item){
            $this->data_anggota[$k]['id'] = $item->no_anggota_platinum;
            $this->data_anggota[$k]['text'] = $item->no_anggota_platinum .'/'. $item->name;
        }

        $this->start_transaction();
    }

    public function setSelectedItem($key)
    {
        $this->selected_item = $this->data[$key];
        $this->edit_stock = $this->selected_item['qty'];
    }

    public function updateProduct()
    {
        $this->validate([
            'edit_stock' => 'required'
        ]);

        if($this->selected_item){
            $this->data[$this->selected_item['id']]['qty'] = $this->edit_stock;
        }
        $this->emit('close-modal');
    }

    public function updated($propertyName)
    {
        if($propertyName=='uang_tunai'){
            $this->total_kembali = 0;
            if(replace_idr($this->uang_tunai) >0 and $this->total_and_ppn >0){
                $this->total_kembali = replace_idr($this->uang_tunai)-$this->total_and_ppn;
            }
        }

        $this->ppn = 0;//$this->total*0.11;
        $this->total_and_ppn = $this->total + $this->ppn;
        if($this->edit_stock=="") $this->edit_stock = 0;
    }

    public function deleteAnggota()
    {
        $this->reset('anggota','no_anggota','temp_anggota');
    }

    public function setAnggota($no_anggota='')
    {
        if($no_anggota) $this->no_anggota = $no_anggota;
        $this->validate([
            'no_anggota'=>'required'
        ]);
        $this->reset('msg_error_anggota');
        $this->temp_anggota = UserMember::where('no_anggota_platinum',$this->no_anggota)->first();
        if(!$this->temp_anggota){
            $this->msg_error_anggota = "No Anggota tidak ditemukan";
            return;
        }
    }

    public function okeAnggota()
    {
        $this->anggota = $this->temp_anggota;
        $this->emit('close-modal-input-anggota');
    }

    public function event_bayar($transaction_id)
    {
        if($transaction_id!=$this->transaksi->no_transaksi) return;

        $this->bayar();
    }

    public function bayar()
    {
        $validate['total'] = 'required';

        if(in_array($this->metode_pembayaran,[7,8])){
            $validate['no_kartu_debit_kredit'] = 'required';
        }

        $this->validate($validate);

        $this->reset('message_metode_pembayaran');
        //1 Tunai
        if($this->metode_pembayaran==4){
            if(replace_idr($this->uang_tunai)<$this->total_and_ppn){
                $this->message_metode_pembayaran = "Uang Tunai tidak mencukupi untuk melakukan transaksi";   
                return;
            }
            $this->transaksi->uang_tunai = replace_idr($this->uang_tunai);
            $this->transaksi->uang_tunai_change = replace_idr($this->uang_tunai) - $this->total_and_ppn;
        }

        // 3 Bayar Nanti
        if($this->metode_pembayaran==3){
            if(!isset($this->anggota->id)){
                $this->message_metode_pembayaran = "Anggota harus diisi terlebih dahulu";
                return;
            }
            $sisa = $this->anggota->plafond - $this->anggota->plafond_digunakan;
            //cek kuota
            if($sisa<$this->total_and_ppn){
                $this->message_metode_pembayaran = "Saldo Limit tidak mencukupi untuk melakukan transaksi";   
                return;
            }else{
                $this->anggota->plafond_digunakan = $this->anggota->plafond_digunakan + $this->total_and_ppn;
                $this->anggota->save();

                SyncCoopzone::dispatch(
                    [
                        'url'=>'koperasi/user/edit',
                        'field'=>'plafond_digunakan',
                        'value'=>$this->anggota->plafond_digunakan,
                        'no_anggota'=>$this->anggota->no_anggota_platinum
                    ]
                );
            }   
        }

        if($this->metode_pembayaran==5){
            if(!isset($this->anggota->id)){
                $this->message_metode_pembayaran = "Anggota harus diisi terlebih dahulu";
                return;
            }
            if($this->anggota->simpanan_ku<$this->total_and_ppn){
                $this->message_metode_pembayaran = "Saldo COOPAY tidak mencukupi untuk melakukan transaksi";   
                return;
            }else{
                $this->anggota->simpanan_ku = $this->anggota->simpanan_ku - $this->total_and_ppn;
                $this->anggota->save();
                // Sinkron Coopzone
                // SyncCoopzone::dispatch(
                //     [
                //         'url'=>'koperasi/user/edit',
                //         'field'=>'simpanan_ku',
                //         'value'=>$this->anggota->simpanan_ku,
                //         'no_anggota'=>$this->anggota->no_anggota_platinum
                //     ]
                // );
            }
        }
        
        if($this->jenis_transaksi==1){
            // Sinkron Coopzone
            // SyncCoopzone::dispatch(
            //     [
            //         'url'=>'koperasi/notifikasi/store',
            //         'no_anggota'=>$this->anggota->no_anggota_platinum,
            //         'message'=>"Kamu telah melakukan transaksi sebesar Rp. ".format_idr($this->total_and_ppn).' di '.get_setting('company'),
            //         'title'=>'Transaksi #'.$this->transaksi->no_transaksi.' berhasil'
            //     ]
            // );
        }

        if($this->anggota) {
            $this->transaksi->user_member_id = $this->anggota->id;
            $this->transaksi->jenis_transaksi = 1;
        }else{
            $this->transaksi->jenis_transaksi = 2;
        }
        
        // kurangin stock
        foreach($this->transaksi->items as $item){
            Product::find($item->product_id)->update(['qty'=>$item->product->qty - $item->qty,'qty_moving'=>$item->product->qty_moving+$item->qty]);
        }
        
        /**
         * Jika bukan paylater maka payment date kosong 
         */
        if($this->metode_pembayaran!=3) $this->transaksi->payment_date = date('Y-m-d');

        $this->transaksi->metode_pembayaran = $this->metode_pembayaran;
        $this->transaksi->is_temp = 0;
        $this->transaksi->no_kartu_debit_kredit = $this->no_kartu_debit_kredit;
        $this->transaksi->status = 1;
        $this->transaksi->reseller_id = Auth::user()->reseller_id;
        $this->transaksi->save();

        $this->url_cetak_struk = route('transaksi.cetak-struk',$this->transaksi->id)."#toolbar=0&navpanes=0&scrollbar=0";
        
        event(new \App\Events\GeneralNotification("Transaksi kasir #{$this->transaksi->no_transaksi} <br /> Total : ".format_idr($this->transaksi->amount)));

        $this->data = [];$this->total=0;$this->sub_total=0;$this->success = true;
        $this->status_transaksi=0;
        $this->emit('active-popup','popup-success-transaksi');
        $this->reset_transaksi();
    }

    public function reset_transaksi()
    {
        $this->uang_tunai = 0;$this->total_kembali = 0;$this->total_qty = 0;
        $this->ppn = 0;$this->total_and_ppn = 0;
        $this->reset('transaksi',
                    'anggota',
                    'uang_tunai',
                    'temp_anggota',
                    'no_kartu_debit_kredit');
    }

    public function cetakStruk()
    {
        $this->emit('on-print',$this->url_cetak_struk);
    }

    public function getProduct($kode_produksi="")
    {
        if($kode_produksi) $this->kode_produksi = $kode_produksi;
        
        if($this->kode_produksi=="") return;
        $this->validate([
            'kode_produksi' => 'required'
        ]);

        if($this->transaksi==""){
            $this->start_transaction();
        }

        $product  = Product::where('kode_produksi',$this->kode_produksi)->first();
        $this->reset('msg_error');
        
        if($this->qty=="" || $this->qty==0) $this->qty = 1;

        if($product){
            if($product->harga_jual=="" || $product->harga_jual==0){
                $this->msg_error = "Harga produk masih kosong.";
                return;
            }
            if(isset($this->data[$product->id])){
                $this->data[$product->id]['qty'] += $this->qty;
                $trans = TransaksiItem::where(['product_id'=>$product->id,'transaksi_id'=>$this->transaksi->id])->first();
                if($trans) { 
                    $trans->qty = $this->data[$product->id]['qty'];
                    $trans->total = $this->data[$product->id]['qty']*$this->data[$product->id]['harga_jual'];
                    $trans->save();
                }
            }else{
                if($product) $this->data[$product->id]['id'] = $product->id;
                if($product) $this->data[$product->id]['kode_produksi'] = $product->kode_produksi;
                if($product) $this->data[$product->id]['keterangan'] = $product->keterangan;
                if($product) $this->data[$product->id]['harga_jual'] = $product->harga_jual;
                if($product) $this->data[$product->id]['qty'] = $this->qty;
                if($product) $this->data[$product->id]['stock'] = $product->qty;

                $transaksi_item = new TransaksiItem();
                $transaksi_item->transaksi_id = $this->transaksi->id;
                $transaksi_item->qty = $this->qty;
                $transaksi_item->product_id = $product->id;
                $transaksi_item->description = $product->keterangan;
                $transaksi_item->price = $product->harga_jual;
                $transaksi_item->harga = $product->harga;
                $transaksi_item->ppn = $product->ppn;
                $transaksi_item->margin = $product->margin;
                $transaksi_item->diskon = $product->diskon;    
                $transaksi_item->total = $transaksi_item->price * $transaksi_item->qty;
                $transaksi_item->save();
            }

            $this->qty = 1;
        }else $this->msg_error = "Maaf produk tidak ditemukan.";

        $this->sub_total = 0;$this->total = 0;$this->total_qty=0;
        foreach($this->data as $i){
            $this->sub_total += $i['harga_jual']*$i['qty'];;
            $this->total += $i['harga_jual']*$i['qty'];;
            $this->total_qty += $i['qty'];
        }
                
        $this->ppn = 0;
        $this->total_and_ppn = $this->total + $this->ppn;
        $this->transaksi->amount = $this->total_and_ppn;
        $this->transaksi->save();
                
        $this->reset('kode_produksi');$this->emit('clear-barcode');
    }

    public function delete($k)
    {
        $find = TransaksiItem::where(['product_id'=>$this->data[$k]['id'],'transaksi_id'=>$this->transaksi->id])->first();
        if($find) {
            $find->delete();
            unset($this->data[$k]);
        }
    }

    public function cancel_transaction()
    {
        $this->transaksi->status = 2;
        $this->transaksi->is_temp = 0;
        $this->transaksi->save(); $this->reset('transaksi');
        $this->data = [];$this->total = 0;$this->sub_total = 0;
        $this->status_transaksi = 0;
        $this->reset_transaksi();
    }

    public function start_transaction()
    {
        $transaksies = Transaksi::where(['user_id'=>Auth::user()->id,'is_temp'=>1])->get();
        if($transaksies->count()>0){
            foreach($transaksies as $transaksi){
                TransaksiItem::where('transaksi_id',$transaksi->id)->delete();
                $transaksi->delete();
            }
        }

        $data = Transaksi::create([
            'is_temp'=>1,
            'user_id'=>Auth::user()->id,
            'reseller_id'=>Auth::user()->reseller_id,
            'jenis_transaksi'=>$this->jenis_transaksi
        ]);

        $data->update(['no_transaksi'=>$data->id.date('ymdhi').str_pad((Transaksi::count()+1),4, '0', STR_PAD_LEFT)]);

        $this->emit('set_transaction_id',$data->no_transaksi);
        $this->status_transaksi = 1;
        $this->transaksi = $data;
    }
}