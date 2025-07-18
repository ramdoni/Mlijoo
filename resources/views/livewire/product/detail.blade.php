@section('title', $data->kode_produksi .' / '.$data->keterangan)
@section('parentPageTitle', 'Produk')
<div class="row clearfix">
    <div class="col-lg-8 col-sm-8 col-md-8 px-0 mx-0">
        <div class="card mb-2">
            <div class="body">
                <h6>Detail Produk</h6>
                <hr />
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <th style="border:0">Kode Produksi</th>
                                <td style="border:0"> : </td>
                                <td style="border:0">@livewire('product.editable',['field'=>'kode_produksi','data'=>$data->kode_produksi,'id'=>$data->id],key('kode_produksi'.$data->id))</td>
                            </tr>
                            <tr>
                                <th>Produk</th>
                                <td> : </td>
                                <td>@livewire('product.editable',['field'=>'keterangan','data'=>$data->keterangan,'id'=>$data->id],key('keterangan'.$data->id))</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td> : </td>
                                <td>@livewire('product.editable',['field'=>'type','data'=>$data->type,'id'=>$data->id],key('type'.$data->id))</td>
                            </tr>
                            <tr>
                                <th>Kode Item</th>
                                <td> : </td>
                                <td>@livewire('product.editable',['field'=>'item_code','data'=>$data->item_code,'id'=>$data->id],key('item_code'.$data->id))</td>
                            </tr>
                            <tr>
                                <th>UOM</th>
                                <td> : </td>
                                <td>{{isset($data->uom->name) ? $data->uom->name : '-'}}</td>
                            </tr>
                            <tr>
                                <th>Stok</th>
                                <td style="width:10px;"> : </td>
                                <td>{{$data->qty}}</td>
                            </tr>
                            <tr>
                                <th>Stok Terjual</th>
                                <td style="width:10px;"> : </td>
                                <td>{{$data->qty_moving}}</td>
                            </tr>
                            <tr>
                                <th>Minimum Stok</th>
                                <td style="width:10px;"> : </td>
                                <td>@livewire('product.editable',['field'=>'minimum_stok','data'=>$data->minimum_stok,'id'=>$data->id],key('minimum_stok'.$data->id))</td>
                            </tr>
                            <tr>
                                <th>Last Update</th>
                                <td style="width:10px;"> : </td>
                                <td>{{date('d-M-Y',strtotime($data->updated_at))}}</td>
                            </tr>
                            <tr>
                                <th>Image</th>
                                <td style="width:10px;"> : </td>
                                <td>
                                    @if($data->image and $file=="")
                                        <img src="{{ asset($data->image) }}" width="100">
                                    @endif
                                    <input type="file" class="form-control" wire:model="file" >
                                    @if ($file)
                                        <div class="form-group mt-2">
                                            <label for="">Preview</label><br />
                                            <img src="{{$file->temporaryUrl()}}" width="100">
                                        </div>
                                    @endif
                                    @error('file')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        @if(strlen($data->kode_produksi)>10 and is_numeric($data->kode_produksi))
                            <div>
                                <label>Barcode</label>
                                <a href="{{route('transaksi.cetak-barcode',$data->kode_produksi)}}" class="ml-3" target="_blank"><i class="fa fa-print"></i> Cetak</a>
                                {!! DNS1D::getBarcodeHTML($data->kode_produksi, 'EAN13')!!}
                                <label>
                                    070222456789
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-4 col-md-4 pr-0 mx-0">
        <div class="card mb-2">
            <div class="body">  
                <h6>Kalkulator Harga</h6>
                <hr />
                <form id="basic-form" method="post" wire:submit.prevent="update">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Jual Dasar</label>
                                <input type="number" class="form-control" wire:model="harga" />
                                <small class="text-info">*harga jual sebelum pajak</small>
                            </div>
                            <div class="form-group mb-0">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" wire:model="is_ppn" value="1">
                                    <span>Pajak {{$ppn ? "(" .format_idr($ppn) .")" : ''}}</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="mb-0">Harga Produksi : {{format_idr($harga_produksi)}}</label><br />
                                <small class="text-info">*Harga Jual Dasar + Pajak)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Jual (Rp)</label>
                                <input type="number" class="form-control" wire:model="harga_jual" />
                            </div>
                            <div class="form-group">
                                <label>Diskon (Rp)</label>
                                <input type="number" class="form-control" wire:model="diskon" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Harga Jual - Diskon</label>
                            <h2 class="text-info">Rp. {{@format_idr($harga_jual - $diskon)}}</h2>
                        </div>
                        <div class="col-md-12">
                            <hr />
                            <button type="submit" wire:loading.remove wire:target="update" class="btn btn-info"><i class="fa fa-save"></i> Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 px-0 mx-0">
        <div class="card mb-2">
            <div class="body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#tab_pembelian">{{ __('Pembelian') }} </a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_penjualan">{{ __('Penjualan') }} </a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_supplier">{{ __('Supplier') }} </a></li>
                </ul>
                <div class="tab-content px-0">
                    <div class="tab-pane" id="tab_supplier">
                        <div class="table-responsive">
                            <table class="table table-hover m-b-0 c_list table-bordered">
                                <thead style="background: #eee;">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Supplier</th>                                 
                                        <th>No Telepon</th>
                                        <th>Alamat</th>
                                        <th>Email</th>
                                        <th>Created At</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_supplier as $k => $item)
                                    <tr>
                                        <td style="width: 50px;">{{$k+1}}</td>
                                        <td><a href="{{route('user-supplier.listproduk',['data'=>$item->id])}}" class="{{$item->status==4?"text-danger" : ""}}">{{$item->nama_supplier?$item->nama_supplier:'-'}}</a></td>
                                        <td>@livewire('user-supplier.editable',['field'=>'no_telp','data'=>$item->no_telp,'id'=>$item->id],key('no_telp'.$item->id))</td>
                                        <td>@livewire('user-supplier.editable',['field'=>'alamat_supplier','data'=>$item->alamat_supplier,'id'=>$item->id],key('alamat_supplier'.$item->id))</td>
                                        <td>@livewire('user-supplier.editable',['field'=>'email','data'=>$item->email,'id'=>$item->id],key('email'.$item->id))</td>
                                        <td>@livewire('user-supplier.editable',['field'=>'created_at','data'=>$item->created_at,'id'=>$item->id],key('created_at'.$item->id))</td>
                                        
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-navicon"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <a class="dropdown-item" href="{{route('user-member.edit',['id'=>$item->id])}}"><i class="fa fa-search-plus"></i> Detail</a>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0)" wire:click="delete({{$item->id}})"><i class="fa fa-trash"></i> Hapus</a>
                                                </div>
                                            </div>    
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane active show" id="tab_pembelian">
                        <div class="table-responsive">
                            <div class="row mb-3">
                                <div class="col-2">
                                    <input type="text" class="form-control" placeholder="Pencarian" />
                                </div>
                                <!-- <div class="col-2">
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_form_pembelian" class="btn btn-info"><i class="fa fa-plus"></i> Tambah</a>
                                </div> -->
                            </div>
                            <table class="table m-b-0 c_list table-hover table-bordered">
                                <thead>
                                    <tr style="background: #eee;">
                                        <th>No</th>
                                        <th>Requester</th>
                                        <th>PR Number</th>
                                        <th>PR Date</th>
                                        <th>PO Number</th>
                                        <th>PO Date</th>
                                        <th>DO Number</th>
                                        <th>Receipt Date</th>
                                        <th>Price</th>
                                        <th>Unit</th>
                                        <th>Total</th>
                                        <th>Total Margin</th>
                                        <th>Expired Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembelian as $k => $item)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>{{$item->requester}}</td>
                                            <td>{{$item->pr_number}}</td>
                                            <td>{{$item->pr_date}}</td>
                                            <td>{{$item->po_number}}</td>
                                            <td>{{$item->po_date}}</td>
                                            <td>{{$item->do_number}}</td>
                                            <td>{{$item->receipt_date}}</td>
                                            <td>{{format_idr($item->price)}}</td>
                                            <td class="text-center">{{$item->qty}}</td>
                                            <td>{{format_idr($item->total)}}</td>
                                            <td>{{format_idr($item->total_margin)}}</td>
                                            <td>{{$item->expired_date}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_penjualan">
                        <div class="table-responsive">
                            <table class="table table-striped m-b-0 c_list table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Transaksi</th>
                                        <th>Harga Jual</th>
                                        <th>QTY</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                @foreach($penjualan as $k => $item)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>
                                            @if(isset($item->transaksi->no_transaksi))
                                                <a href="{{route('transaksi.items',$item->transaksi_id)}}" target="_blank">{{$item->transaksi->no_transaksi}}</a>
                                            @endif
                                        </td>
                                        <td>{{format_idr($item->price)}}</td>
                                        <td>{{format_idr($item->qty)}}</td>
                                        <td>{{format_idr($item->total)}}</td>
                                        <td>{{date('d-M-Y',strtotime($item->created_at))}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form_pembelian" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            @livewire('product.form-pembelian',['data'=>$data->id])
        </div>
    </div>
</div>

@push('after-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <script>
        setTimeout(() => {
            select__2 = $('.select_anggota').select2();
            $('.select_anggota').on('change', function (e) {
                var data = $(this).select2("val");
                @this.set("user_member_id", data);
            });
        }, 1000);
    </script>
@endpush