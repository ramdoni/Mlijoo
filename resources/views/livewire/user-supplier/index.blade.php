@section('title', 'Supplier')
@section('sub-title', 'Index')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Pencarian" />
                </div>
                <!-- <div class="col-md-2 px-0">
                    <select class="form-control" wire:model="status">
                        <option value=""> --- Status --- </option>
                        <option value="1">Inactive</option>
                        <option value="2">Active</option>
                        <option value="5">Keluar</option>
                    </select>
                </div> -->
                <div class="col-md-6">
                    <!-- <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="javascript:void(0);" wire:click="downloadExcel"><i class="fa fa-download"></i> Download</a>
                            <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal" data-target="#modal_upload"><i class="fa fa-upload"></i> Upload</a>
                        </div>
                    </div> -->
                    <a href="javascript:void(0)" wire:click="$set('insert',true)" class="btn btn-info"><i class="fa fa-plus"></i> Supplier</a>
                    <!-- <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_upload"><i class="fa fa-upload"></i> Upload</a> -->
                    <span wire:loading wire:targer="keyword">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </div>
            </div>
            <div class="body pt-0">
                <div class="table-responsive" style="min-height:400px;">
                    <table class="table table-hover m-b-0 c_list table-bordered">
                        <thead style="background: #eee;">
                            <tr>
                                <th>No</th>
                                <th>Nama Supplier</th>   
                                <th>Tipe Supplier</th>                              
                                <th>Email</th>
                                <th>No Telepon</th>
                                <th>Alamat</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($insert)
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="nama_supplier" />
                                        @error('nama_supplier') <span class="text-danger">{{ $message }}</span> @enderror
                                        @if($error_nama_supplier) <span class="text-danger">{{ $error_nama_supplier }}</span> @endif
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="tipe_supplier" />
                                        @error('tipe_supplier') <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="email" />
                                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="no_telp" />
                                        @error('no_telp') <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="alamat_supplier" />
                                        @error('alamat_supplier') <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" wire:click="save" class="btn btn-info"><i class="fa fa-save"></i> Simpan</a>
                                        <a href="javascript:void(0)" wire:click="$set('insert',false)" class="btn btn-danger"><i class="fa fa-close"></i> Batal</a>
                                    </td>
                                </tr>
                            @endif
                            @php($number= $data->total() - (($data->currentPage() -1) * $data->perPage()) )
                            @foreach($data as $k => $item)
                            <tr>
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td><a href="{{route('user-supplier.listproduk',['data'=>$item->id])}}" class="{{$item->status==4?"text-danger" : ""}}">{{$item->nama_supplier?$item->nama_supplier:'-'}}</a></td>
                                <td>@livewire('user-supplier.editable',['field'=>'tipe_supplier','data'=>$item->tipe_supplier,'id'=>$item->id],key('tipe_supplier'.$item->id))</td>
                                <td>@livewire('user-supplier.editable',['field'=>'email','data'=>$item->email,'id'=>$item->id],key('email'.$item->id))</td>
                                <td>@livewire('user-supplier.editable',['field'=>'no_telp','data'=>$item->no_telp,'id'=>$item->id],key('no_telp'.$item->id))</td>
                                <td>@livewire('user-supplier.editable',['field'=>'alamat_supplier','data'=>$item->alamat_supplier,'id'=>$item->id],key('alamat_supplier'.$item->id))</td>
                                
                                <td>{{ date_format(date_create($item->created_at), 'd M Y') }}</td>
                                
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-navicon"></i></a>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <a class="dropdown-item" href="{{route('user-supplier.listproduk',['data'=>$item->id])}}"><i class="fa fa-search-plus"></i> Detail</a>
                                            <a class="dropdown-item text-danger" href="javascript:void(0)" wire:click="delete({{$item->id}})"><i class="fa fa-trash"></i> Hapus</a>
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

<div class="modal fade" id="modal_upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <livewire:user-supplier.upload />
        </div>
    </div>
</div>