<div>
    <div class="row">
        <div class="col-md-2">
            <select class="form-control">
                <option value=""> -- Tahun -- </option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control" wire:model="filter.jenis_simpanan_id">
                <option value=""> -- Jenis Simpanan -- </option>
                <option value="1">Simpanan Pokok</option>
                <option value="2">Simpanan Wajib</option>
                <option value="3">Simpanan Sukarela</option>
                <option value="4">Simpanan Lain-lain</option>
                <option value="5">Bunga Simpanan Pokok</option>
            </select>
        </div>
        <div class="col-md-8">
            {{-- <a href="javascript:void(0)" data-target="#modal_add_simpanan" data-toggle="modal" class="btn btn-info"><i class="fa fa-plus"></i> Tambah</a> --}}
            {{-- <a href="javascript:void(0)" data-target="#modal_setting_simpanan" data-toggle="modal" class="btn btn-warning"><i class="fa fa-gear"></i> Pengaturan</a> --}}
        </div>
    </div>
    <div class="table-responsive mt-3">
        <table class="table table-hover m-b-0 c_list">
            <thead>
                <tr style="background:#eee;">
                    <th style="width:50">No</th>
                    {{-- <th>No Transaksi</th> --}}
                    <th class="text-center">Status</th>
                    <th>Jenis Simpanan</th>
                    <th>Keterangan</th>
                    <th>Created</th>
                    <th>Payment Date</th>
                    <th class="text-right">Nominal<br />
                        <label class="text-info">({{format_idr($total_amount)}})</label></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php($number= $data->total() - (($data->currentPage() -1) * $data->perPage()) )
                @foreach($data as $k => $item)
                    <tr>
                        <td style="width: 50px;">{{$number}}</td>
                        {{-- <td>{{$item->no_transaksi}}</td> --}}
                        <td class="text-center">
                            @if($item->status==0)
                                <span class="badge badge-warning">Belum Lunas</span>
                            @endif
                            @if($item->status==1)
                                <span class="badge badge-success">Lunas</span>
                            @endif
                        </td>
                        <td>{{isset($item->jenis_simpanan->name) ? $item->jenis_simpanan->name : '-'}}</td>
                        <td>{{$item->description}}</td>
                        <td>{{date('d-M-Y',strtotime($item->created_at))}}</td>
                        <td>{{$item->payment_date ? date('d-M-Y',strtotime($item->payment_date)) : '-'}}</td>
                        <td class="text-right">{{format_idr($item->amount)}}</td>
                        <td>
                            @if($item->status==0)
                                <a href="javascript:void(0)" class="badge badge-info badge-active" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_simpanan_bayar"><i class="fa fa-check"></i> Bayar</a>
                            @endif
                        </td>
                    </tr>
                    @php($number--)
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@push('after-scripts')
    <script>
        Livewire.on('close-modal',()=>{
            $('.modal').modal('close');
        });
    </script>
@endpush

@livewire('user-member.add-simpanan',['data'=>$member->id])
@livewire('user-member.simpanan-bayar',['data'=>$member->id])
@livewire('user-member.setting-simpanan')