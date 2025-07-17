@section('title', 'Transaksi')
@section('sub-title', 'Home')
<div class="clearfix row">
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <i class="fa fa-shopping-cart text-info"></i>
                </div>
                <div class="content">
                    <div class="text">Penjualan hari ini</div>
                    <h5 class="number">Rp. {{format_idr($penjualan_hari_ini)}}</h5>
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
                    <h5 class="number">{{$transaksi_hari_ini}}</h5>
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
                    <h5 class="number">Rp. {{format_idr($penjualan_bulan_ini)}}</h5>
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
                    <h5 class="number">{{format_idr($transaksi_bulan_ini)}}</h5>
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
                                <div class="from-group my-2" wire:ignore>
                                    <select class="form-control" id="anggota">
                                        <option value=""> -- Anggota -- </option>
                                        @foreach($user_members as $k => $item)
                                            <option value="{{$item->id}}">{{$item->no_anggota_platinum}} / {{$item->name}}</option>
                                        @endforeach
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
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="javascript:void(0);" wire:click="downloadExcel"><i class="fa fa-download"></i> Download</a>
                            <a href="javascript:void(0)" wire:click="$set('cetak_invoice',true)" class="dropdown-item"><i class="fa fa-print"></i> Cetak Invoice</a>
                        </div>
                    </div>
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
                                <th class="text-center pt-2">
                                    @if($cetak_invoice==true)
                                        <p>
                                            <a href="javascript:void(0)" class="btn btn-info" wire:click="$emit('calc_invoice')" data-toggle="modal" data-target="#modal_submit_invoice"><i class="fa fa-check-circle"></i> Submit Invoice ({{count(array_filter($check_id))}})</a>
                                        </p>    
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" value="1" wire:model="check_all" wire:click="check_all_">
                                            <span> Check All</span>
                                        </label>
                                    @endif
                                </th>
                                <th>Status</th>
                                <th>Reseller</th>
                                <th>No Transaksi</th>
                                <th class="text-center">Metode Pembayaran</th>
                                <th>Tanggal Transaksi</th>
                                <th class="text-center">Tanggal Pembayaran</th>
                                <th class="text-right">Nominal</th>
                                <th class="text-right">PPN</th>
                                <th class="text-right">Total<br />
                                    <label class="text-info">(Rp {{format_idr($total)}})</label>
                                </th>
                                <th></th>
                           </tr>
                        </thead>
                        <tbody>
                            @php($number= $data->total() - (($data->currentPage() -1) * $data->perPage()) )
                            @foreach($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;" class="text-center">{{$number}}</td>
                                    <td class="text-center">
                                        @if($cetak_invoice==true and $item->status==1 and $item->payment_date=="")
                                            <label class="fancy-checkbox">
                                                <input type="checkbox" value="{{$item->id}}" wire:model="check_id.{{$k}}">
                                                <span></span>
                                            </label>
                                        @endif
                                    </td>
                                    <td>{!!status_transaksi($item->status)!!}</td>
                                    <td>{{isset($item->reseller->nama) ? $item->reseller->nama : '-'}}</td>
                                    <td><a href="{{route('transaksi.items',$item->id)}}">{{$item->no_transaksi}}</a></td>
                                    <td class="text-center">{{$item->metode_pembayaran ? metode_pembayaran($item->metode_pembayaran) : 'TUNAI'}}</td>
                                    <td>{{date('d-M-Y H:i',strtotime($item->created_at))}}</td>
                                    <td class="text-center">{{$item->payment_date ? date('d-M-Y',strtotime($item->payment_date)) : '-'}}</td>
                                    <td class="text-right">{{format_idr($item->amount - ($item->amount * 0.11))}}</td>
                                    <td class="text-right">{{format_idr($item->amount * 0.11)}}</td>
                                    <td class="text-right">{{format_idr($item->amount)}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-navicon"></i></a>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                @if($item->status !=1)
                                                    <a href="javascript:void(0)" wire:click="$emit('void',{{$item->id}})" class="dropdown-item text-danger"><i class="fa fa-close"></i> Void</a>
                                                @endif
                                                <a href="{{route('transaksi.items',$item->id)}}" class="dropdown-item"><i class="fa fa-info"></i> Detail</a>
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

    <div wire:ignore.self class="modal fade" id="modal_submit_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="submitInvoice">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-print"></i> Invoice</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">                        
                        @error('check_id')
                            <div class="form-group">
                                <span class="text-danger">{{ $message }}</span>
                            </div> 
                        @enderror
                        <div class="form-group">
                            <small>No Invoice</small><br />
                            <h6>{{$no_invoice}}</h6>
                            <hr class="mt-0" />
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <small>Total Transaksi</small><br />
                                <h6>{{count(array_filter($check_id))}}</h6>
                                <hr class="mt-0" />
                            </div>
                            <div class="form-group col-md-8">
                                <small>Total Amount</small><br />
                                <h6>Rp. {{format_idr($total_invoice)}}</h6>
                                <hr class="mt-0" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Due Date</label>
                            <input type="date" class="form-control" wire:model="due_date" />
                            @error('due_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <table class="table">
                                <tr>
                                    <th>No</th>
                                    <th>No Transaksi</th>
                                    <th>Tanggal Transaksi</th>
                                    <th class="text-right">Nominal</th>
                                </tr>
                                @if(count(array_filter($check_id))>1)
                                    @foreach(\App\Models\Transaksi::whereIn('id',$check_id)->get() as $k => $item)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td><a href="{{route('transaksi.items',$item->id)}}" target="_blank">{{$item->no_transaksi}}</a></td>
                                            <td>{{date('d-M-Y H:i',strtotime($item->created_at))}}</td>
                                            <td class="text-right">{{format_idr($item->amount)}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modal_set_password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="changePassword">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-sign-in"></i> Set Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" class="form-control" wire:model="password" />
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger close-modal">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modal_void" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="voidTransaksi">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-sign-in"></i> Void Transaksi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Alasan</label>
                            <input type="text" class="form-control" wire:model="alasan" />
                            @error('alasan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger close-modal">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="modal_autologin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-sign-in"></i> Autologin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger close-modal">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <livewire:transaksi.upload />
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirm_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-warning"></i> Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <p>Are you want delete this data ?</p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">No</button>
                <button type="button" wire:click="delete()" class="btn btn-danger close-modal">Yes</button>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/daterangepicker.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterange/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <style>
        .select2-container .select2-selection--single {height:36px;padding-left:10px;}
        .select2-container .select2-selection--single .select2-selection__rendered{padding-top:1px;}
        .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
        .select2-container {width: 100% !important;}
    </style>
    <script>
        var select_anggota = $('#anggota').select2({
                placeholder: " -- Anggota -- ",
            }
        );
        $('#anggota').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('filter.user_member_id',data);
        });

        Livewire.on('clear-filter',()=>{
            $("#anggota").val('').trigger('change');
        });

        Livewire.on('void',(id)=>{
            $("#modal_void").modal('show');
        });
        Livewire.on('clear-filter',()=>{
            $("#anggota").val('').trigger('change');
        });
        $('.tanggal_transaksi').daterangepicker({
            opens: 'left',
            locale: {
                cancelLabel: 'Clear'
            },
            autoUpdateInput: false,
        }, function(start, end, label) {
            @this.set("filter_created_start", start.format('YYYY-MM-DD'));
            @this.set("filter_created_end", end.format('YYYY-MM-DD'));
            $('.tanggal_transaksi').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
        });
        
</script>
@endpush