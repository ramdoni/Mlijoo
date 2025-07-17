@section('title', 'Purchase Order')
@section('sub-title', 'Insert')
<div class="row clearfix">
    <div class="col-md-6">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <small>No Purchase Order</small>
                        <h6>{{$no_po}}</h6>
                        <hr class="py-0 my-0" />
                    </div>
                    <div class="form-group col-md-6 text-right">
                        <small>Status</small><br />
                        <span class="badge badge-info mr-0">Draft</span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Supplier</label>
                        <select class="form-control" wire:model="id_supplier">
                            <option value=""> -- Pilih -- </option>
                            @foreach($suppliers as $item)
                                <option value="{{$item->id}}">{{$item->nama_supplier}}</option>
                            @endforeach
                        </select>
                        @error('id_supplier') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Biaya Pengiriman</label>
                        <input type="number" class="form-control" wire:model="biaya_pengiriman" />
                        
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Pengiriman</label>
                    <textarea class="form-control" wire:model="alamat_penagihan"></textarea>
                    @error('alamat_penagihan') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="body">  
                <ul class="nav nav-tabs-new2">
                    <li class="nav-item"><a class="nav-link {{$tab_active =='tab-supplier' ? 'active show' : ''}}" data-toggle="tab" href="#tab_detail_supplier" wire:click="$set('tab_active','tab-supplier')">Detail Supplier</a></li>
                    <li class="nav-item"><a class="nav-link {{$tab_active =='tab-product' ? 'active show' : ''}}" data-toggle="tab" href="#tab_product_supplier" wire:click="$set('tab_active','tab-product')">Produk Supplier</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane {{$tab_active =='tab-supplier' ? 'show active' : ''}}" id="tab_detail_supplier">
                        <div class="form-group">
                            <small>Supplier</small><br />
                            <label>{{isset($supplier->nama_supplier) ? $supplier->nama_supplier : '-'}}</label>
                            <hr class="mt-0 pt-0" />
                        </div>
                        <div class="form-group">
                            <small>Telepon</small><br />
                            <label>{{isset($supplier->no_telp) ? $supplier->no_telp : '-'}}</label>
                            <hr class="mt-0 pt-0" />
                        </div>
                        <div class="form-group">
                            <small>Alamat</small><br />
                            <label>{{isset($supplier->alamat_supplier) ? $supplier->alamat_supplier : '-'}}</lab>
                            <hr class="mt-0 pt-0" />
                        </div>
                    </div>
                    <div class="tab-pane {{$tab_active =='tab-product' ? 'show active' : ''}}" id="tab_product_supplier">
                        <div class="table-responsive">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <input type="text" class="form-control" placeholder="Pencarian" />
                                </div>
                            </div>
                            <table class="table c_list table-hover table-bordered">
                                <thead  style="background: #eee;">
                                    <tr>
                                        <th></th>
                                        <th>Barcode</th>
                                        <th>Produk</th>
                                        <th>UOM</th>
                                        <th>Harga</th>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product_supplier as $k => $item)
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-info btn-sm" wire:click="addProductSupplier({{$item->id}})"><i class="fa fa-plus"></i></a>
                                            </td>
                                            <td>{{isset($item->product->kode_produksi) ? $item->product->kode_produksi : '-'}}</td>
                                            <td>{{isset($item->product->keterangan) ? $item->product->keterangan : '-'}}</td>
                                            <td>{{isset($item->uom->name) ? $item->uom->name : '-'}}</td>
                                            <td class="text-center">{{$item->qty}}</td>
                                            <td class="text-right">{{format_idr($item->price)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="body">
                <h6>Produk Purchase Order</h6>
                <hr />
                <div class="table-responsive">
                    <table class="table c_list table-hover table-bordered">
                        <thead>
                            <tr style="background: #eee;">
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Produk</th>
                                <th>UOM</th>
                                <th>QTY</th>
                                <th>Harga</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td colspan="2">
                                    <div wire:ignore>
                                        <select class="form-control select_product_po">
                                            <option value=""> -- Barcode / Produk -- </option>
                                        </select>
                                    </div>
                                    @error('product_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <select class="form-control" wire:model="product_uom_id">
                                        <option value=""> --- UOM --- </option>
                                        @foreach(\App\Models\ProductUom::get() as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach 
                                    </select>
                                    @error('product_uom_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <input type="number" class="form-control" wire:model="qty" min="0" />
                                    @error('qty') <span class="text-danger">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <input type="number" class="form-control text-right" min="0" wire:model="price" />
                                    @error('proc_nice') <span class="text-danger">{{ $message }}</span> @enderror
                                </td>
                                <td><a href="javascript:void(0)" class="btn btn-info btn-sm" wire:click="addProduct"><i class="fa fa-plus"></i></a></td>
                            </tr>
                            @foreach($product_po as $k => $item)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$item['barcode']}}</td>
                                    <td>{{$item['keterangan']}}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background: #eee;">
                            <tr>
                                <th colspan="4" class="text-right">Total</th>
                                <th class="text-center">0</th>
                                <th class="text-right">0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <hr />
                <div class="form-group">
                    <button type="button" class="btn btn-info"><i class="fa fa-save"></i> Submit Purchase Order</button>
                </div>
            </div>
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

        select_product_po = $('.select_product_po').select2({
                    placeholder: " -- Barcode / Product -- ",
                    data : {!!json_encode($data_product)!!}
                });
            $('.select_product_po').on('change', function (e) {
                var data = $(this).select2("val");
                @this.set("product_id", data);
            });
    </script>
@endpush