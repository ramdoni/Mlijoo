@section('title', 'Supplier')
@section('sub-title', $data->nama_supplier)
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Pencarian" />
                </div>
                <!-- <div class="col-md-10">
                    <a href="javascript:void(0)" class="btn btn-info" wire:click="$set('insert_product',true)"><i class="fa fa-plus"></i> Tambah</a>
                </div> -->
            </div>
            <div class="body pt-0">
                <div class="table-responsive" style="min-height:250px; overflow: scroll;">
                    <table class="table table-hover m-b-0 c_list ">
                        <thead style="background: #eee;">                        
                            <tr>
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Produk</th>
                                <th>Deskripsi</th>
                                <th>UOM</th>
                                <th class="text-center">Stock Supplier</th>
                                <th class="text-right">Harga Jual</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($insert_product)
                                <!-- <tr>
                                    <td></td>
                                    <td colspan="2">
                                        <div wire:ignore>
                                            <select class="form-control" id="barcode">
                                                <option value=""> -- BARCODE -- </option>
                                            </select>
                                        </div>
                                        @error('product_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Deskripsi" wire:model="desc_product" />
                                        @error('desc_product') <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <select class="form-control" wire:model="product_uom_id">
                                            <option value=""> --- UOM --- </option>
                                            @foreach(\App\Models\ProductUom::get() as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach 
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="Stock" wire:model="qty" />
                                        @error('qty') <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <input type="number" class="form-control text-right" placeholder="Harga Jual" wire:model="price" />
                                        @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <span wire:loading wire:targer="saveProduct">
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">{{ __('Loading...') }}</span>
                                        </span>
                                        <div wire:loading.remove wire:target="saveProduct">
                                            <a href="javascript:void(0)" class="btn btn-info" wire:click="saveProduct"><i class="fa fa-save"></i></a>
                                            <a href="javascript:void(0)" class="btn btn-danger" wire:click="$set('insert_product',false)"><i class="fa fa-close"></i></a>
                                        </div>
                                    </td>
                                </tr> -->
                            @endif
                            @php($number= $products->total() - (($products->currentPage() -1) * $products->perPage()) )
                            @foreach($products as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{$k+1}}</td>
                                    <td>{{isset($item->product->kode_produksi) ? $item->product->kode_produksi : '-'}}</td>
                                    <td>
                                        @if(isset($item->product->keterangan))
                                            <a href="{{route('product.detail',$item->product_id)}}">{{$item->product->keterangan}}</a>
                                        @else
                                         - 
                                        @endif
                                    </td>
                                    <td>{{$item->desc_product}}</td>
                                    <td>{{isset($item->uom->name) ? $item->uom->name : '-'}}</td>
                                    <td class="text-center">{{$item->qty}}</td>
                                    <td class="text-right">{{format_idr($item->price)}}</td>
                                    <td></td>
                                </tr>
                                @php($number--)
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>
                
            </div>
        </div>
    </div>

    {{-- <div class="col-md-12">
        <div class="card mb-2">
            <div class="body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#tab_pembelian">{{ __('Pembelian') }} </a></li>
                </ul>
                <div class="tab-content px-0">
                    <div class="tab-pane active show" id="tab_pembelian">
                        <div class="table-responsive">
                            <div class="row mb-3">
                                <div class="col-2">
                                    <input type="text" class="form-control" placeholder="Pencarian" />
                                </div>
                                <div class="col-2">
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_form_pembelian" class="btn btn-info"><i class="fa fa-plus"></i> Tambah</a>
                                    <a href="javascript:void(0)" wire:click="$set('insert',true)" class="btn btn-warning"><i class="fa fa-plus"></i> Tambah</a>
                                </div>
                            </div>
                            <table class="table m-b-0 c_list table-hover">
                                <thead>
                                    <tr style="background: #eee;">
                                        <th>No</th>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Diskon</th>
                                        <th>Total Harga</th>
                                        <th>Created Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($insert)
                                    <tr>
                                        <td></td>
                                        <td>
                                            <input type="text" class="form-control" wire:model="produk" />
                                            @error('produk') <span class="text-danger">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" wire:model="price" />
                                            @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" wire:model="qty" />
                                            @error('qty') <span class="text-danger">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" wire:model="diskon" />
                                            @error('diskon') <span class="text-danger">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" wire:model="total_price" readonly />
                                            @error('total_price') <span class="text-danger">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly />
                                            
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" wire:click="save" class="btn btn-info"><i class="fa fa-save"></i> Simpan</a>
                                            <a href="javascript:void(0)" wire:click="$set('insert',false)" class="btn btn-danger"><i class="fa fa-close"></i> Batal</a>
                                        </td>
                                    </tr>
                                    @endif
                                    
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>{{$item->item}}</td>
                                            <td>{{$item->price}}</td>
                                            <td>{{$item->qty}}</td>
                                            <td>{{$item->disc}}</td>
                                            <td>{{$item->total_price}}</td>
                                            <td>{{$item->created_at}}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-navicon"></i></a>
                                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                        <a class="dropdown-item text-danger" href="javascript:void(0)" wire:click="delete({{$item->id}})"><i class="fa fa-trash"></i> Hapus</a>
                                                    </div>
                                                </div>    
                                            </td>
                                        </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
     --}}
</div>
@push('after-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
    <style>
        .select2-container .select2-selection--single {height:36px;padding-left:10px;}
        .select2-container .select2-selection--single .select2-selection__rendered{padding-top:1px;}
        .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
        .select2-container {width: 100% !important;}
    </style>
    <script>
        Livewire.on('insert-product',()=>{
            setTimeout(function(){
                var select_barcode = $('#barcode').select2({
                        placeholder: " -- BARCODE -- ",
                        data : {!!json_encode($data_product)!!}
                });

                $('#barcode').on('change', function (e) {
                    var data = $(this).select2("val");
                    @this.set('product_id',data);
                });
            },500)
        });
    </script>
@endpush