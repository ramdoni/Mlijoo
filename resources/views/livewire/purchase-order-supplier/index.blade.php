@section('title', 'Purchase Order')
@section('sub-title', 'Index')
<div class="clearfix row">
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <i class="fa fa-shopping-cart text-info"></i>
                </div>
                <div class="content">
                    <div class="text">Total Nominal</div>
                    <h5 class="number">Rp. {{format_idr($total_po)}}</h5>
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
                    <div class="text">Lunas</div>
                    <h5 class="number">Rp. {{format_idr(0)}}</h5>
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
                    <div class="text">Belum Lunas</div>
                    <h5 class="number">Rp. {{format_idr(0)}}</h5>
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
                    <div class="text">Total QTY</div>
                    <h5 class="number">{{format_idr(0)}}</h5>
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
                                <div class="form-group">
                                    <small>Tanggal Transaksi</small>
                                    <input type="text" class="form-control tanggal_transaksi" />
                                </div>
                                <a href="javascript:void(0)" wire:click="clear_filter()"><small>Clear filter</small></a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="javascript:void(0);" wire:click="downloadExcel"><i class="fa fa-download"></i> Download</a>
                            <a href="javascript:void(0)" wire:click="insert" class="dropdown-item"><i class="fa fa-plus"></i> Tambah</a>
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
                                <th class="text-center">Status</th>
                                <th>No Purchase Order</th>
                                <th>Supplier</th>
                                <th>Tanggal Transaksi</th>
                                <th>Total Produk</th>
                                <th>Total Qty</th>
                                <th class="text-right">Biaya Pengiriman</th>
                                <th class="text-right">Pajak</th>
                                <th class="text-right">Total Nominal</th>
                                <th class="text-right">Sisa Bayar</th>
                                <th></th>
                           </tr>
                        </thead>
                        <tbody>
                            @php($number= $data->total() - (($data->currentPage() -1) * $data->perPage()) )
                            @foreach($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;" class="text-center">{{$number}}</td>
                                    <td class="text-center">
                                        @if($item->status==1)
                                            <span class="badge badge-info">PO Request</span>
                                        @endif
                                        @if($item->status==2)
                                            <span class="badge badge-warning">Invoice Sent</span>
                                        @endif
                                        @if($item->status==3)
                                            <span class="badge badge-warning">Waiting for Confirmation</span>
                                        @endif
                                        @if($item->status==4)
                                            <span class="badge badge-success">Payment Confirm</span>
                                        @endif
                                        @if($item->status==5)
                                            <span class="badge badge-success">Deliver</span>
                                        @endif
                                    </td>
                                    <td><a href="{{route('purchase-order-supplier.detail',$item->id)}}">{{$item->no_po}}</a></td>
                                    <td>
                                        {{isset($item->supplier->nama_supplier) ? $item->supplier->nama_supplier : '-'}}
                                        @if(isset($item->supplier->nama_supplier))
                                            <a href="javascript:void(0)" title="{!!$item->supplier->nama_supplier .'&#013;'. $item->supplier->alamat_supplier!!}"><i class="fa fa-info-circle"></i></a>
                                        @endif
                                    </td>
                                    <td>{{date('d-M-Y H:i',strtotime($item->created_at))}}</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">{{format_idr($item->biaya_pengiriman)}}</td>
                                    <td class="text-right">{{format_idr($item->ppn)}}</td>
                                    <td class="text-right">Rp, {{format_idr($item->total_pembayaran)}}</td>
                                    <td class="text-right">
                                        <?php
                                            if(\App\Models\InvoicePoItem::where('po_id', $item->id)->get()){
                                                $sisa_bayar = $item->total_pembayaran - \App\Models\InvoicePoItem::where('po_id', $item->id)->sum('amount');
                                                echo 'Rp, '.format_idr($sisa_bayar);
                                            }else{
                                                echo 'Rp, '.format_idr($item->total_pembayaran);
                                            }
                                            
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-navicon"></i></a>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a href="{{route('purchase-order-supplier.detail',$item->id)}}" class="dropdown-item"><i class="fa fa-info"></i> Detail</a>
                                                @if($item->status==4)
                                                    <a href="{{route('purchase-order-supplier.insert-delivery-order',$item->id)}}" class="dropdown-item"><i class="fa fa-plus"></i> Delivery Order</a>
                                                @endif
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
    <script>
        Livewire.on('void',(id)=>{
            $("#modal_void").modal('show');
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