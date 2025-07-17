@section('title', 'Invoice')
@section('sub-title', $data->no_invoice)
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                <h6>Detail Inovice</h6>
                <hr />
                <div class="row">
                    <div class="col-md-4">
                        <table class="table">
                            <tr>
                                <th style="border:0">No Invoice</th>
                                <td style="border:0"> : </td>
                                <td style="border:0">{{$data->no_invoice}}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pengajuan</th>
                                <td> : </td>
                                <td>{{date('d-M-Y',strtotime($data->created_at))}}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td> : </td>
                                <td>
                                    @if($data->status==0)
                                        <label class="badge badge-warning">Belum Lunas</label>
                                    @endif
                                    @if($data->status==1)
                                        <label class="badge badge-success">Lunas</lab>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Due Date</th>
                                <td> : </td>
                                <td>{{$data->due_date ? date('d-M-Y',strtotime($data->due_date)) : '-'}}</td>
                            </tr>
                            <tr>
                                <th>Payment Date</th>
                                <td> : </td>
                                <td>{{$data->payment_date ? date('d-M-Y',strtotime($data->payment_date)) : '-'}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table">
                            <tr>
                                <th style="border:0">Metode Pembayaran</th>
                                <td style="border:0;width:10px;"> : </td>
                                <td style="border:0">{{$data->metode_pembayaran ? metode_pembayaran($data->metode_pembayaran) : '-'}}</td>
                            </tr>
                            @if($data->file)
                                <tr>
                                    <th>Bukti Pembayaran</th>
                                    <td> : </td>
                                    <td><a href="{{asset($data->file)}}" target="_blank"><i class="fa fa-image"></i></a></td>
                                </tr>
                            @endif
                            <tr>
                                <th>Total Transaksi</th>
                                <td> : </td>
                                <td>{{$data->total_item}}</td>
                            </tr>
                            <tr>
                                <th>Total Nominal</th>
                                <td> : </td>
                                <td>{{format_idr($data->amount)}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <hr />
                        @if($data->status==0)
                            <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_upload_bukti_pembayaran"><i class="fa fa-upload"></i> Bayar</a>
                            <a href="javascript:void(0)" class="btn btn-danger"><i class="fa fa-close"></i> Batal</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                <h6>Detail Transaksi</h6>
                <hr />
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr style="background: #eee;">
                            <th>No</th>
                            <th>No Transaksi</th>
                            <th>No Anggota</th>
                            <th>Nama Anggota</th>
                            <th>Tanggal Transaksi</th>
                            <th class="text-right">Nominal</th>
                        </tr>
                        @foreach($data->items as $k => $item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td><a href="{{route('transaksi.items',$item->id)}}" target="_blank">{{$item->transaksi->no_transaksi}}</a></td>
                                <td>{{isset($item->transaksi->anggota->no_anggota_platinum) ? $item->transaksi->anggota->no_anggota_platinum : '-'}}</td>
                                <td><a href="{{route('user-member.edit',$item->transaksi->user_member_id)}}" target="_blank">{{isset($item->transaksi->anggota->name) ? $item->transaksi->anggota->name : '-'}}</a></td>
                                <td>{{date('d-M-Y H:i',strtotime($item->transaksi->created_at))}}</td>
                                <td class="text-right">{{format_idr($item->transaksi->amount)}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modal_upload_bukti_pembayaran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="bayar">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-upload"></i> Upload Bukti Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal Pembayaran</label>
                            <input type="date" class="form-control" wire:model="payment_date" />
                            @error('payment_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Jumlah Bayar</label>
                            <input type="text" class="form-control" wire:model="payment_amount" />
                            @error('payment_amount') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Bukti Pembayaran</label>
                            <input type="file" class="form-control" wire:model="file_bukti_pembayaran" />
                            @error('file_bukti_pembayaran') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Metode Pembayaran</label>
                            <select class="form-control" wire:model="metode_pembayaran">
                                <option value=""> -- Pilih -- </option>
                                <option value="4">Tunai</option>
                                <option value="9">Transfer</option>
                            </select>
                            @error('file_bukti_pembayaran') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span wire:loading wire:target="bayar">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <button wire:loading.remove wire:target="bayar" type="submit" class="btn btn-info"><i class="fa fa-check-circle"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>