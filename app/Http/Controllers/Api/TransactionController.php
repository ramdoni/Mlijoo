<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Product;
use App\Models\UserMember;
use App\Models\TransaksiItem;
use App\Events\KasirEvent;
use App\Jobs\SyncCoopzone;

class TransactionController extends Controller
{
    public $status = 0,$message;

    public function submitQrcode(Request $r)
    {
        $validator = \Validator::make($r->all(), [
            'no_anggota'=> 'required',
            'token'=>'required',
            'amount'=>'required',
            'metode_pembayaran'=>'required'
        ]);

        if ($validator->fails()){
            $msg = '';
            foreach ($validator->errors()->getMessages() as $key => $value) {
                $msg .= $value[0]."\n";
            }
            return response()->json(['status'=>0,'message'=>$msg], 200);
        }

        $transaksi = Transaksi::where(['amount'=>$r->amount,'is_temp'=>1])->first();

        if($r->amount < 0 || !$transaksi) return response()->json(['status'=>3,'message'=>"Transaksi tidak bisa dilakukan karna harga tidak sesuai"], 200);
        
        $member = UserMember::where('no_anggota_platinum',$r->no_anggota)->first();
    
        if(!$member) return response()->json(['status'=>3,'message'=>"Anggota tidak ditemukan"], 200);
        // 3 Bayar Nanti
        if($r->metode_pembayaran==3){
            $sisa = $member->plafond - $member->plafond_digunakan;
            //cek kuota
            if($sisa<$r->amount)
                return response()->json(['status'=>3,'message'=>"Kuota tidak mencukupi untuk melakukan transaksi"], 200);
            else{
                $this->status = 1;
                $transaksi->payment_date = date('Y-m-d');
                $transaksi->is_temp = 0;
                $transaksi->status = 1;

                $member->plafond_digunakan = $member->plafond_digunakan + $r->amount;
                $member->save();
                
                event(new KasirEvent('Pembayaran berhasil dilakukan',$transaksi->no_transaksi));

                // Sinkron Coopzone
                $response = sinkronCoopzone([
                    'url'=>'koperasi/user/edit',
                    'field'=>'plafond_digunakan',
                    'value'=>$member->plafond_digunakan,
                    'no_anggota'=>$member->no_anggota_platinum
                ]);
            }   
        }

        if($r->metode_pembayaran==5){
            if($member->simpanan_ku<$r->amount)
                return response()->json(['status'=>3,'message'=>"Saldo Coopay tidak mencukupi untuk melakukan transaksi"], 200);
            else{
                $this->status = 1;
                $member->simpanan_ku = $member->simpanan_ku - $r->amount;
                $member->save();

                // Sinkron Coopzone
                $response = sinkronCoopzone([
                    'url'=>'koperasi/user/edit',
                    'field'=>'simpanan_ku',
                    'value'=>$member->simpanan_ku,
                    'no_anggota'=>$member->no_anggota_platinum
                ]);

                $transaksi->payment_date = date('Y-m-d');
                $transaksi->is_temp = 0;
                $transaksi->status = 1;

                event(new KasirEvent('Pembayaran berhasil dilakukan',$transaksi->no_transaksi));
            }
        }
        
        $transaksi->save();

        return response()->json(['status'=>$this->status,'message'=>$this->message], 200);
    }

    public function storePulsa(Request $r)
    {
        $validator = \Validator::make($r->all(), [
            'transaction_id'=> 'required',
            'no_anggota'=> 'required',
            'token'=>'required'
        ]);
        
        if($r->token != env('COOPZONE_TOKEN')) return response()->json(['status'=>'failed','message'=>'Token Invalid'], 200);

        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->errors()->getMessages() as $key => $value) {
                $msg .= $value[0]."\n";
            }
            return response()->json(['status'=>'failed','message'=>$msg], 200);
        }
        
        $member = UserMember::where('no_anggota_platinum',$r->no_anggota)->first();

        $transaksi = Transaksi::where('no_transaksi',$r->transaction_id)->first();
        if(!$transaksi){
            $transaksi = new Transaksi();
            $transaksi->no_transaksi = $r->transaction_id;
            $transaksi->user_member_id = $member?$member->id:0;
            $transaksi->tanggal_transaksi = date('Y-m-d',strtotime($r->date));
            $transaksi->metode_pembayaran = $r->metode_pembayaran;
            // if($trmetode_pembayaran==4) $transaksi->payment_date = date('Y-m-d',strtotime($tanggal));
            $transaksi->status = $r->status;
            $transaksi->save();
        }
        
        $item = new TransaksiItem();
        $item->transaksi_id = $transaksi->id;
        $item->description = $r->product;
        $item->qty = $r->qty;
        $item->price = $r->price;
        $item->total = $r->total;
        $item->save();

        $transaksi->amount = TransaksiItem::where('transaksi_id',$transaksi->id)->sum('price');
        $transaksi->save();

        $is_transaction = false;

        if($r->price <0) return response()->json(['status'=>3,'message'=>"Transaksi tidak bisa dilakukan karna harga tidak sesuai"], 200);

        // 3 Bayar Nanti
        if($r->metode_pembayaran==3){
            $sisa = $member->plafond - $member->plafond_digunakan;
            //cek kuota
            if($sisa<$r->price)
                return response()->json(['status'=>3,'message'=>"Kuota tidak mencukupi untuk melakukan transaksi"], 200);
            else{
                $this->status = 1;
                $member->plafond_digunakan = $member->plafond_digunakan + $r->price;
                $member->save();

                // Sinkron Coopzone
                $response = sinkronCoopzone([
                    'url'=>'koperasi/user/edit',
                    'field'=>'plafond_digunakan',
                    'value'=>$member->plafond_digunakan,
                    'no_anggota'=>$member->no_anggota_platinum
                ]);
            }   
        }

        if($r->metode_pembayaran==5){
            if($member->simpanan_ku<$r->price)
                return response()->json(['status'=>3,'message'=>"Saldo DIDOMPET tidak mencukupi untuk melakukan transaksi"], 200);
            else{
                $this->status = 1;
                $member->simpanan_ku = $member->simpanan_ku - $r->price;
                $member->save();

                // Sinkron Coopzone
                $response = sinkronCoopzone([
                    'url'=>'koperasi/user/edit',
                    'field'=>'simpanan_ku',
                    'value'=>$member->simpanan_ku,
                    'no_anggota'=>$member->no_anggota_platinum
                ]);

                $transaksi->payment_date = date('Y-m-d');
            }
        }

        if($this->status==1){
            $response = digiflazz([
                'id'=>$transaksi->id,
                'product'=>$r->product_code,
                'no'=>$r->reference_no,
                'action'=>'topup',
                'ref_id'=>$r->transaction_id
            ]);

            $transaksi->api_response_before = $response;
            $transaksi->save();

            $response = json_decode($response);
            if(isset($response->data->rc) and $response->data->rc=='00'){ // sukses
                $transaksi->status = 1; $this->status = 1;
                $transaksi->save();
            }
        }

        return response()->json(['status'=>$this->status,'message'=>$this->message], 200);
    }

    public function detail(Request $r)
    {
        $validator = \Validator::make($r->all(), [
            'token'=>'required'
        ]);
        
        if($r->token != env('COOPZONE_TOKEN')) return response()->json(['status'=>'failed','message'=>'Token Invalid'], 200);

        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->errors()->getMessages() as $key => $value) {
                $msg .= $value[0]."\n";
            }
            return response()->json(['status'=>'failed','message'=>$msg], 200);
        }

        $transaksi = Transaksi::find($r->id);   
        
        $data['no_transaksi'] = $transaksi->no_transaksi;
        $data['jenis_transaksi'] = $transaksi->jenis_transaksi==1?'Angota' : 'Non Anggota';
        $data['date'] = date('d-F-Y',strtotime($transaksi->created_at));
        $data['status'] = status_transaksi($transaksi->status);

        $data['products'] = [];
        foreach($transaksi->items as $k => $item){
            $data['products'][$k]['id'] = $item->id;
            $data['products'][$k]['kode_produksi'] = $item->product->kode_produksi;
            $data['products'][$k]['keterangan'] = $item->product->keterangan;
            $data['products'][$k]['qty'] = $item->qty;
            $data['products'][$k]['price'] = format_idr($item->price);
            $data['products'][$k]['total'] = format_idr($item->total);
        }

        \LogActivity::add('[Kasir] Transaction Detail #'. $transaksi->id);

        return response()->json(['status'=>'success','data'=>$data], 200);
    }

    public function store(Request $r)
    {
        $validator = \Validator::make($r->all(), [
            'token'=>'required'
        ]);
        
        if($r->token != env('COOPZONE_TOKEN')) return response()->json(['status'=>'failed','message'=>'Token Invalid'], 200);

        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->errors()->getMessages() as $key => $value) {
                $msg .= $value[0]."\n";
            }
            return response()->json(['status'=>'failed','message'=>$msg], 200);
        }
        
        $jenis_transaksi = 1; $anggota="";
        if($r->metode_pembayaran==4) $jenis_transaksi = 2; // Non Anggota
        if($r->metode_pembayaran==3){
            $anggota = UserMember::find($r->anggota_id);
        }

        /**
         * 4 Pembayaran Tunai
         */
        if($r->metode_pembayaran==4){
            if(replace_idr($r->uang_tunai)<$r->total){
                return response()->json(['status'=>'failed','message'=>'Uang Tunai tidak mencukupi untuk melakukan transaksi'], 200);
            }
        }

        /**
         * 3 Bayar Nanti
         * */ 
        if($r->metode_pembayaran==3){
            if(!isset($anggota->id)){
                return response()->json(['status'=>'failed','message'=>'Anggota harus diisi terlebih dahulu'], 200);
            }
            $sisa = $anggota->plafond - $anggota->plafond_digunakan;
            //cek kuota
            if($sisa<$r->total){   
                return response()->json(['status'=>'failed','message'=>'Saldo Limit tidak mencukupi untuk melakukan transaksi'], 200);
            }else{
                $anggota->plafond_digunakan = $anggota->plafond_digunakan + $r->total;
                $anggota->save();
                SyncCoopzone::dispatch([
                        'url'=>'koperasi/user/edit',
                        'field'=>'plafond_digunakan',
                        'value'=>$anggota->plafond_digunakan,
                        'no_anggota'=>$anggota->no_anggota_platinum
                    ]
                );
            }   
        }
        
        $data = new Transaksi();
        // $data->user_id = \Auth::user()->id;
        $data->jenis_transaksi = $jenis_transaksi;
        $data->metode_pembayaran = $r->metode_pembayaran;
        $data->amount = $r->total;
        $data->is_temp = 0;
        $data->status = 1;
        $data->save();
        $data->no_transaksi =  $data->id.date('ymdhi').str_pad((Transaksi::count()+1),4, '0', STR_PAD_LEFT);
        $data->save();

        if($r->metode_pembayaran==4){
            $data->uang_tunai = replace_idr($r->uang_tunai);
            $data->uang_tunai_change = replace_idr($r->uang_tunai) - $r->total;
        }

        if($r->jenis_transaksi==1){
            // Sinkron Coopzone
            SyncCoopzone::dispatch(
                [
                    'url'=>'koperasi/notifikasi/store',
                    'no_anggota'=>$anggota->no_anggota_platinum,
                    'message'=>"Kamu telah melakukan transaksi sebesar Rp. ".format_idr($r->total).' di '.get_setting('company'),
                    'title'=>'Transaksi #'.$data->no_transaksi.' berhasil'
                ]
            );
        }

        if($anggota) {
            $data->user_member_id = $anggota->id;
            $data->jenis_transaksi = 1;
        }else{
            $data->jenis_transaksi = 2;
        }
        
        foreach($r->items as $item){
            $product = Product::find($item['id']);

            $transaksi_item = new TransaksiItem();
            $transaksi_item->transaksi_id = $data->id;
            $transaksi_item->qty = $item['qty_beli'];
            $transaksi_item->product_id = $item['id'];
            $transaksi_item->description = $item['keterangan'];
            $transaksi_item->price = $item['harga_number'];
            $transaksi_item->harga = $product->harga;
            $transaksi_item->ppn = $product->ppn;
            $transaksi_item->margin = $product->margin;
            $transaksi_item->diskon = $product->diskon;
            $transaksi_item->total = $transaksi_item->price * $transaksi_item->qty;
            $transaksi_item->save();
            
            Product::find($transaksi_item->product_id)->update(['qty'=>$transaksi_item->product->qty - $transaksi_item->qty,'qty_moving'=>$transaksi_item->product->qty_moving+$transaksi_item->qty]);
        }
        
        /**
         * Jika bukan paylater maka payment date kosong 
         */
        if($r->metode_pembayaran!=3) $data->payment_date = date('Y-m-d');


        $data->save();

        $url_cetak_struk = route('transaksi.cetak-struk',$data->id)."#toolbar=0&navpanes=0&scrollbar=0";

        $param['type'] = 'transaksi';
        $param['transaksi'] = $data;
        $param['transaksi_items'] = $data->items->toArray();

        \App\Jobs\JobKoperasi::dispatch($param);

        return response()->json(['status'=>'success','url_cetak_struk'=>$url_cetak_struk], 200);
    }

    public function update(Request $r)
    {
        $validator = \Validator::make($r->all(), [
            'transaction_id'=> 'required',
            'no_anggota'=> 'required',
            'token'=>'required'
        ]);
        
        if($r->token != env('COOPZONE_TOKEN')) return response()->json(['status'=>'failed','message'=>'Token Invalid'], 200);

        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->errors()->getMessages() as $key => $value) {
                $msg .= $value[0]."\n";
            }
            return response()->json(['status'=>'failed','message'=>$msg], 200);
        }
        
        $member = UserMember::where('no_anggota_platinum',$r->no_anggota)->first();

        $transaksi = Transaksi::where('no_transaksi',$r->transaction_id)->first();
        if($transaksi){
            $transaksi->status = $r->status;
            $transaksi->api_response_after = $r->api_response_after;
            $transaksi->data_json = $r->data_json;
            $transaksi->save();
        }

        return response()->json(['status'=>$this->status,'message'=>''], 200);
    }

    public function data(Request $r)
    {
        $validator = \Validator::make($r->all(), [
            'token'=>'required'
        ]);
        
        if($r->token != env('COOPZONE_TOKEN')) return response()->json(['status'=>'failed','message'=>'Token Invalid'], 200);

        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->errors()->getMessages() as $key => $value) {
                $msg .= $value[0]."\n";
            }
            return response()->json(['status'=>'failed','message'=>$msg], 200);
        }
        
        $transaksi = Transaksi::orderBy('id','DESC');
        $temp = [];
        foreach($transaksi->paginate(100) as $k => $item){
            $temp[$k]['id'] = $item->id;
            $temp[$k]['status'] = $item->status;
            $temp[$k]['no_transaksi'] = $item->no_transaksi;
            $temp[$k]['jenis_transaksi'] = $item->jenis_transaksi;
            $temp[$k]['no_anggota'] = isset($item->anggota->no_anggota_platinum) ? $item->anggota->no_anggota_platinum : '-';
            $temp[$k]['nama_anggota'] = isset($item->anggota->name) ? $item->anggota->name : '-';
            $temp[$k]['metode_pembayaran'] = $item->metode_pembayaran ? metode_pembayaran($item->metode_pembayaran) : '-';
            $temp[$k]['created_at'] = date('d-M-Y H:i',strtotime($item->created_at));
            $temp[$k]['status_pembayaran'] = $item->payment_date ? 'Lunas' : 'Belum Lunas';
            $temp[$k]['payment_date'] = $item->payment_date ? date('d-M-Y',strtotime($item->payment_date)) : '-';
            $temp[$k]['sub_total'] = format_idr($item->amount - ($item->amount * 0.11));
            $temp[$k]['ppn'] = format_idr($item->amount * 0.11);
            $temp[$k]['total'] = format_idr($item->amount);
            $temp[$k]['struk'] = route('transaksi.cetak-struk-kasir',$item->id)."#toolbar=0&navpanes=0&scrollbar=0";
        }
        
        return response()->json(['status'=>$this->status,'data'=>$temp], 200);
    }
}