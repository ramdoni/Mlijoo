@section('title', 'Produk')
@section('sub-title', 'Index')
<div class="clearfix row">
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
                                    <select class="form-control" wire:model="filter.status">
                                        <option value=""> -- Status -- </option>
                                        <option value="1"> Aktif</option>
                                        <option value="0"> Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="from-group my-2">
                                    <input type="text" class="form-control" wire:model="filter.keterangan" placeholder="Pencarian" />
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
                            <a href="{{route('product-supplier.insert')}}" class="dropdown-item"><i class="fa fa-plus"></i> Tambah</a>
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
                                <th class="text-center">Status</th>
                                <th class="text-center">Type</th>
                                <th>Kode Produksi / Barcode</th>
                                <th>Produk</th>
                                <th>UOM</th>
                                <th class="text-center">Stok</th>
                               
                                <th class="text-right">Harga Jual Dasar</th>
                                <th class="text-right">PPN</th>
                                <th class="text-right">Harga Produksi</th>
                                <th class="text-right">Harga Jual</th>
                                <th class="text-right">Diskon</th>
                                <th class="text-right">Harga Jual + Diskon</th>
                                <th></th>
                           </tr>
                        </thead>
                        <tbody>
                            @php($number= $data->total() - (($data->currentPage() -1) * $data->perPage()) )
                            @foreach($data as $k => $item)
                                @php($bg_minimum_stok="transparent")
                                @php($title="")
                                @if($item->minimum_stok>0)
                                    @if($item->minimum_stok>$item->qty)
                                        @php($bg_minimum_stok="#ff000057")
                                        @php($title="Dibawah minum stock")
                                    @endif
                                    @if($item->minimum_stok==$item->qty)
                                        @php($bg_minimum_stok="#fff10057")
                                        @php($title="Sudah memasuki minimum stock")
                                    @endif
                                @endif
                                <tr style="background: {{$bg_minimum_stok}}" title="{{$title}}">
                                    <td style="width: 50px;">{{$number}}</td>
                                    <td class="text-center">
                                        @if($item->status==1)
                                            <span class="badge badge-success">Aktif</span>
                                        @endif
                                        @if($item->status==0 || $item->status=="")
                                            <span class="badge badge-default">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{$item->type}}</td>
                                    <td>@livewire('product-supplier.editable',['field'=>'barcode','data'=>$item->barcode,'id'=>$item->id],key('barcode'.$item->id))</td>
                                    <td><a href="{{route('product-supplier.detail',$item->id)}}">{{$item->desc_product}}</a></td>
                                    <td>@livewire('product-supplier.editable',['field'=>'product_uom_id','data'=>(isset($item->uom->name) ? $item->uom->name : ''),'id'=>$item->id],key('uom'.$item->id))</td>
                                    <td class="text-center">@livewire('product-supplier.editable',['field'=>'qty','data'=>$item->qty,'id'=>$item->id],key('qty'.$item->id))</td>
                                   
                                    <td class="text-right">
                                        {{$item->harga ? format_idr($item->price) : '-'}}
                                    </td>
                                    <td class="text-right">{{$item->ppn ? format_idr($item->ppn) : '-'}}</td>    
                                    <td class="text-right">{{$item->harga_produksi ? format_idr($item->harga_produksi) : '-'}}</td>    
                                    <td class="text-right">{{$item->harga_jual ? format_idr($item->harga_jual) : '-'}}</td>    
                                    <td class="text-right">{{$item->diskon ? format_idr($item->diskon) : '-'}}</td>    
                                    <td class="text-right">{{$item->harga_jual ? format_idr($item->harga_jual - $item->diskon) : '-'}}</td>    
                                    <td></td>
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
</div>

<div class="modal fade" id="modal_upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <livewire:product.upload />
        </div>
    </div>
</div>



