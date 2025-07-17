@section('title', 'Transaksi')
@section('sub-title', $data->no_transaksi)
<div class="clearfix row">
    <div class="col-lg-4">
        <div class="card">
            <div class="body">
                <table class="table">
                    <tr>
                        <th>No Transaksi</th>
                        <td style="width:10px"> : </td>
                        <td>{{$data->no_transaksi}}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Transaksi</th>
                        <td style="width:10px"> : </td>
                        <td>{{date('d-M-Y',strtotime($data->created_at))}}</td>
                    </tr>
                    <tr>
                        <th>Status Transaksi</th>
                        <td style="width:10px"> : </td>
                        <td>
                            {!!status_transaksi($data->status)!!}<br />
                            @if($data->status==4)
                               <code>
                                {{date('d-M-Y',strtotime($data->void_date))}} - {{$data->void_alasan}}
                               </code>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status Pembayaran</th>
                        <td style="width:20px"> : </td>
                        <td>
                            @if($data->payment_date)
                                <span class="badge badge-success">Lunas</span>
                            @else
                                <span class="badge badge-warning">Belum Lunas</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Pembayaran</th>
                        <td style="width:20px"> : </td>
                        <td>{{$data->payment_date ? date('d-M-Y',strtotime($data->payment_date)) : '-'}}</td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td style="width:20px"> : </td>
                        <td>{{$data->metode_pembayaran ? metode_pembayaran($data->metode_pembayaran) : '-'}}
                            @if($data->metode_pembayaran==7 || $data->metode_pembayaran==8)
                             ({{$data->no_kartu_debit_kredit}})
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="body">
                <div class="table-responsive" style="min-height:400px;">
                    <table class="table table-hover table-bordered m-b-0 c_list">
                        <thead style="background: #eee;">
                           <tr>
                                <th style="width:50px">No</th>
                                <th>Barcode</th>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Nominal</th>
                                <th class="text-right">Total</th>
                           </tr>
                        </thead>
                        <tbody>
                            @php($total=0)
                            @foreach($data->items as $k => $item)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$item->product->kode_produksi}}</td>
                                    <td><a href="{{route('product.detail',$item->product_id)}}">{{$item->product->keterangan}}</a></td>
                                    <td class="text-center">{{$item->qty}}</td>
                                    <td class="text-right">{{format_idr($item->price)}}</td>
                                    <td class="text-right">{{format_idr($item->total)}}</td>
                                </tr>
                                @php($total+=$item->total)
                           @endforeach
                        </tbody>
                        <tfoot style="background: #eee;">
                            <tr>
                                <th colspan="5" class="text-right">Sub Total</th>
                                <th class="text-right">{{format_idr($total - ($total * 0.11))}}</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-right">PPN</th>
                                <th class="text-right">{{format_idr($total * 0.11)}}</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-right">Total</th>
                                <th class="text-right">{{format_idr($total)}}</th>
                            </tr>
                            @if($data->metode_pembayaran==4)
                                <tr>
                                    <th colspan="5" class="text-right">Uang Tunai</th>
                                    <th class="text-right">{{format_idr($data->uang_tunai)}}</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Uang Kembali</th>
                                    <th class="text-right">{{format_idr($data->uang_tunai_change)}}</th>
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                    <a href="javascript:void(0)" onclick='document.getElementById("printf").contentWindow.print();' class="btn btn-info btn-sm mt-2"><i class="fa fa-print"></i> Cetak Struk</a>
                </div>
                <br />
            </div>
        </div>
    </div>
    <iframe src="{{route('transaksi.cetak-struk-admin',$data->id)}}#toolbar=0&navpanes=0&scrollbar=0" id="printf" name="printf" style="height:500px;display:none;"></iframe>
</div>

