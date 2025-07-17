@section('title', 'E-Catalog')
@section('sub-title', 'Index')
<div class="clearfix row">
<div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <i class="fa fa-shopping-cart text-info"></i>
                </div>
                <div class="content">
                    <div class="text">Total Supplier</div>
                    <h5 class="number">{{$total_supplier}}</h5>
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
                    <div class="text">Total Produk</div>
                    <h5 class="number">{{format_idr($total_product)}}</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                    <div class="icon text-danger">
                        <i class="fa fa-calendar"></i>
                    </div>
                <div class="content">
                    <div class="text">Penjualan bulan ini</div>
                    <h5 class="number">Rp. 0</h5>
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
                    <h5 class="number">0</h5>
                </div>
            </div>
        </div>
    </div> -->

    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <div class="pl-3 pt-2 form-group mb-0" x-data="{open_dropdown:false}" @click.away="open_dropdown = false">
                        <a href="javascript:void(0)" x-on:click="open_dropdown = ! open_dropdown" class="dropdown-toggle">
                            Filter <i class="fa fa-search-plus"></i>
                        </a>
                        <div class="dropdown-menu show-form-filter" x-show="open_dropdown">
                            <form class="p-2">
                                <div class="from-group my-2">
                                    <input type="text" class="form-control" wire:model="filter.keterangan" placeholder="Pencarian" />
                                </div>
                                <div class="from-group my-2">
                                    <select class="form-control" wire:model="filter.user_id">
                                        <option value=""> -- Supplier -- </option>
                                        @foreach(\App\Models\Supplier\User::where('user_access_id',7)->orderBy('name','ASC')->get() as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <a href="javascript:void(0)" wire:click="$set('filter',[])"><small>Clear filter</small></a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <!-- <a class="dropdown-item" href="javascript:void(0);" wire:click="downloadExcel"><i class="fa fa-download"></i> Download</a> -->
                            <a href="{{route('product.insert')}}" class="dropdown-item"><i class="fa fa-plus"></i> Tambah</a>
                            <a href="javascript:void(0)" class="dropdown-item" wire:click="download"><i class="fa fa-download"></i> Download</a>
                            <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal" data-target="#modal_upload"><i class="fa fa-upload"></i> Upload</a>
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
                                <th>No</th>
                                <th>Supplier</th>
                                <th>Kode Produksi / Barcode</th>
                                <th>Produk</th>
                                <th>UOM</th>
                                <th class="text-right">Harga</th>
                           </tr>
                        </thead>
                        <tbody>
                            @php($number= $data->total() - (($data->currentPage() -1) * $data->perPage()) )
                            @foreach($data as $k => $item)
                            <tr>
                                <td>{{$number}}</td>
                                <td>{{isset($item->user->name) ? $item->user->name : '-'}}</td>
                                <td>{{$item->kode_produksi}}</td>
                                <td>{{$item->keterangan}}</td>
                                <td>{{isset($item->uom->name) ? $item->uom->name : ''}}</td>
                                <td class="text-right">{{format_idr($item->harga_jual)}}</td>
                            </tr>
                            @php($number--)
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br />
            </div>
        </div>
    </div>
</div>