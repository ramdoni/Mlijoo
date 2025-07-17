@section('title', 'Supplier / Produsen')
@section('sub-title', 'Home')

@push('action')
    <div class="text-right">
        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" href="javascript:void(0);" wire:click="download"><i class="fa fa-download"></i> Download</a>
                <a href="javascript:void(0)" wire:click="$set('cetak_invoice',true)" class="dropdown-item"><i class="fa fa-print"></i> Cetak Invoice</a>
            </div>
        </div>
        <a href="{{ route('produsen.create') }}" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Supplier</a>
    </div>
@endpush
<div class="clearfix row">
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <i class="fa fa-shopping-cart text-info"></i>
                </div>
                <div class="content">
                    <div class="text">Penjualan hari ini</div>
                    <h5 class="number">Rp. {{format_idr($totals['penjualan_hari_ini'])}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                    <div class="icon text-warning">
                        <i class="fa fa-database"></i>
                    </div>
                <div class="content">
                    <div class="text">Transaksi hari ini</div>
                    <h5 class="number">{{$totals['transaksi_hari_ini']}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                    <div class="icon text-danger">
                        <i class="fa fa-calendar"></i>
                    </div>
                <div class="content">
                    <div class="text">Penjualan bulan ini</div>
                    <h5 class="number">Rp. {{format_idr($totals['penjualan_bulan_ini'])}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-12">
        <div class="card top_counter currency_state">
            <div class="body">
                    <div class="icon">
                        <i class="fa fa-database text-success"></i>
                    </div>
                <div class="content">
                    <div class="text">Transaksi bulan ini</div>
                    <h5 class="number">{{format_idr($totals['transaksi_bulan_ini'])}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-1">
                    <div class="pl-3 pt-2 form-group mb-0" x-data="{open_dropdown:false}" @click.away="open_dropdown = false">
                        <a href="javascript:void(0)" x-on:click="open_dropdown = ! open_dropdown" class="dropdown-toggle">
                            Filter <i class="fa fa-search-plus"></i>
                        </a>
                        <div class="dropdown-menu show-form-filter" x-show="open_dropdown">
                            <form class="p-2">
                                <div class="from-group my-2">
                                    <select class="form-control" wire:model="filter.status">
                                        <option value=""> -- Status -- </option>
                                        <option value="1"> Sukses</option>
                                        <option value="2"> Batal</option>
                                        <option value="3"> Gagal</option>
                                        <option value="4"> Void</option>
                                    </select>
                                </div>
                                <div class="from-group my-2">
                                    <input type="text" class="form-control" wire:model="filter.no_transaksi" placeholder="No Transaksi" />
                                </div>
                                <div class="from-group my-2">
                                    <select class="form-control" wire:model="filter.pembayaran">
                                        <option value=""> -- Status Pembayaran -- </option>
                                        <option value="1"> Lunas</option>
                                        <option value="2"> Belum Lunas</option>
                                    </select>
                                </div>
                                <div class="from-group my-2">
                                    <select class="form-control" wire:model="filter.jenis_transaksi">
                                        <option value=""> -- Jenis Transaksi -- </option>
                                        <option value="1"> Anggota</option>
                                        <option value="2"> Non Anggota</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <small>Tanggal Transaksi</small>
                                    <input type="text" class="form-control tanggal_transaksi" />
                                </div>
                                <a href="javascript:void(0)" wire:click="clearFilter()"><small>Clear filter</small></a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    
                    <span wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </div>
            </div>
            <div class="body pt-0">
                <div class="table-responsive" style="min-height:400px;">
                    <table class="table table-hover m-b-0 c_list">
                        <thead style="background: #eee;">
                           <tr>
                                <th class="text-center">No</th>
                                <th>Status</th>
                                <th>Nama</th>
                                <th>Spesifikasi</th>
                                <th>Alamat</th>
                                <th>PIC Nama</th>
                                <th>PIC Phone</th>
                                <th class="text-center">Jumlah Produk</th>
                                <th class="text-center">Status Pajak</th>
                                <th class="text-center">Total PO</th>
                                <th class="text-center">Total PO (Amount)</th>
                                <th class="text-center">Total Invoice</th>
                                <th></th>
                           </tr>
                        </thead>
                        <tbody>
                            @php($number= $data->total() - (($data->currentPage() -1) * $data->perPage()) )
                            @foreach($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;" class="text-center">{{$number}}</td>
                                    <td>
                                        @if($item->status==1)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-warning">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td><a href="{{route('produsen.edit',['data'=>$item->id])}}">{{ $item->nama }}</a></td>
                                    <td>{{ Str::limit($item->spesifikasi,80,'...') }}</td>
                                    <td>{{ Str::limit($item->alamat,80,'...') }}</td>
                                    <td>{{ $item->pic_nama }}</td>
                                    <td>{{ $item->pic_phone }}</td>
                                    <td class="text-center">0</td>
                                    <td>{{$item->status_pajak ==1 ? 'PKP' : 'Non PKP'}}</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center">0</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-navicon"></i></a>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a href="{{route('produsen.edit',['data'=>$item->id])}}" class="dropdown-item"><i class="fa fa-info"></i> Detail</a>
                                            </div>
                                        </div>    
                                    </td>
                                </tr>
                                @php($number--)
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br />
                {{$data->links()}}
            </div>
        </div>
    </div>
</div>