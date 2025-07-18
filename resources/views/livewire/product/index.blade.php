@section('title', 'Produk')
@section('sub-title', 'Index')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <div class="pl-3 pt-2 form-group float-left mr-3" x-data="{open_dropdown:false}" @click.away="open_dropdown = false">
                    <a href="javascript:void(0)" x-on:click="open_dropdown = ! open_dropdown" class="dropdown-toggle">
                        Filter <i class="fa fa-search-plus"></i>
                    </a>
                    <div class="dropdown-menu show-form-filter" x-show="open_dropdown">
                        <div class="p-2">
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
                            <div class="from-group my-2">
                                <select class="form-control" wire:model="filter.type">
                                    <option value=""> -- Type -- </option>
                                    <option> Stock</option>
                                    <option> Konsinyasi</option>
                                </select>
                            </div>
                            <div class="from-group my-2">
                                <select class="form-control" wire:model="filter.minimum_stock">
                                    <option value=""> -- Minimum Stock -- </option>
                                    <option value="1"> Under Minimum Stock</option>
                                    <option value="2"> Equal Minimum Stock</option>
                                </select>
                            </div>
                            <a href="javascript:void(0)" wire:click="$set('filter',[])"><small>Clear filter</small></a>
                        </div>
                    </div>
                </div>
                <div class="btn-group float-left" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <!-- <a class="dropdown-item" href="javascript:void(0);" wire:click="downloadExcel"><i class="fa fa-download"></i> Download</a> -->
                        <a href="javascript:void(0)" class="dropdown-item" wire:click="download"><i class="fa fa-download"></i> Download</a>
                        <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal" data-target="#modal_upload"><i class="fa fa-upload"></i> Upload</a>
                    </div>
                </div>
                <a href="{{route('product.insert')}}" class="btn btn-success ml-1"><i class="fa fa-plus"></i> Tambah</a>

                <span wire:loading>
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">{{ __('Loading...') }}</span>
                </span>
            </div>
            <div class="body">
                <div class="table-responsive" style="min-height:400px;">
                    <table class="table table-hover m-b-0 c_list">
                        <thead style="background: #eee;">
                           <tr>
                                <th>No</th>
                                <th class="text-center">Image</th>
                                <!-- <th>Reseller</th> -->
                                <th>Barcode</th>
                                <th>Produk</th>
                                <th>UOM</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Moving Stok</th>
                                <th class="text-center">Mininum Stok</th>
                                <th class="text-right">Harga Jual Dasar</th>
                                <th class="text-right">PPN</th>
                                <th class="text-right">Harga Produksi</th>
                                <th class="text-right">Harga Jual</th>
                                <th class="text-right">Diskon</th>
                                <th class="text-right">Harga Jual + Diskon</th>
                                <th class="text-center">Status</th>
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
                                    <td>
                                        @if($item->image)
                                            <a href="{{ asset($item->image) }}" target="_blank">
                                                <img src="{{ asset($item->image) }}" class="rounded" height="50">
                                            </a>
                                        @endif
                                    </td>
                                    <td><a href="{{route('product.edit',$item->id)}}">{{$item->kode_produksi}}</a></td>
                                    <td><a href="{{route('product.edit',$item->id)}}">{{$item->keterangan}}</a></td>
                                    <td>@livewire('product.editable',['field'=>'product_uom_id','data'=>(isset($item->uom->name) ? $item->uom->name : ''),'id'=>$item->id],key('uom'.$item->id))</td>
                                    <td class="text-center">@livewire('product.editable',['field'=>'qty','data'=>$item->qty,'id'=>$item->id],key('qty'.$item->id))</td>
                                    <td class="text-center">{{$item->qty_moving}}</td>
                                    <td class="text-center"> {{$item->minimum_stok}}</td>
                                    <td class="text-right">{{$item->harga ? format_idr($item->harga) : '-'}}</td>
                                    <td class="text-right">{{$item->ppn ? format_idr($item->ppn) : '-'}}</td>    
                                    <td class="text-right">{{$item->harga_produksi ? format_idr($item->harga_produksi) : '-'}}</td>    
                                    <td class="text-right">{{$item->harga_jual ? format_idr($item->harga_jual) : '-'}}</td>    
                                    <td class="text-right">{{$item->diskon ? format_idr($item->diskon) : '-'}}</td>    
                                    <td class="text-right">{{$item->harga_jual ? format_idr($item->harga_jual - $item->diskon) : '-'}}</td>    
                                    <td>
                                        @if($is_confirm_delete==false)
                                            <a href="javascript:void(0)" class="text-danger" wire:click="set_delete({{$item->id}})"><i class="fa fa-trash"></i></a>
                                        @endif
                                        @if($is_confirm_delete and $selected_id==$item->id)
                                            <a href="javascript:void(0)" class="badge badge-success badge-acive" wire:click="delete">Ya</a>
                                            <a href="javascript:void(0)" class="badge badge-danger badge-acive" wire:click="cancel_delete">Tidak</a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->status==1)
                                            <span class="badge badge-success">Aktif</span>
                                        @endif
                                        @if($item->status==0 || $item->status=="")
                                            <span class="badge badge-default">Tidak Aktif</span>
                                        @endif
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