<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserMember;
use Illuminate\Http\Request;

class UserMemberController extends Controller
{
    public function data(Request $r)
    {   
        $keyword = isset($r->search) ? $r->search : '';

        $data = UserMember::orderBy('keterangan','ASC');

        if($keyword) $data->where(function($table) use($keyword){
                            $table->where('name','LIKE',"%{$keyword}%")->orWhere('no_anggota_platinum','LIKE',"%{$keyword}%");
                        });
        $items = [];
        if(isset($r->all_data) and $r->all_data==1){
            $data = $data->get();
        }else{
            $data = $data->paginate(10);
        }

        foreach($data as $k => $item){
            $items[$k]['id'] = $item->id;
            $items[$k]['nama'] = $item->name;
            $items[$k]['no_anggota'] = $item->no_anggota_platinum;
            $items[$k]['saldo'] = $item->plafond - $item->plafond_digunakan;
            $items[$k]['text'] = $item->no_anggota_platinum .' / '. $item->name . "(".format_idr($item->plafond - $item->plafond_digunakan).")";
        }

        return response()->json(['message'=>'success','items'=>$items,'total_count'=>count($items)], 200);
    }
}