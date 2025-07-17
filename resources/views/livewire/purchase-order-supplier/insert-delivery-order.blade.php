@section('title', 'Delivery Order')
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            @if($data->status==2)
                                <small>DO Number</small><br />
                                <label>{{$data->do_number}}</label>
                                <hr class="py-0 my-0" />
                            @else
                                <label>DO Number</label>
                                <input type="text" class="form-control" wire:model="do_number" />
                                @error('do_number') <span class="text-danger">{{ $message }}</span> @enderror
                            @endif
                        </div>
                        <div class="form-group">
                            @if($data->status==2)
                                <small>Penerima</small><br />
                                <label>{{$data->do_penerima}}</label>
                                <hr class="py-0 my-0" />
                            @else
                                <label>Penerima</label>
                                <input type="text" class="form-control" wire:model="do_penerima" />
                                @error('do_penerima') <span class="text-danger">{{ $message }}</span> @enderror
                            @endif
                        </div>
                        <div class="form-group">
                            @if($data->status==2)
                                <small>Tanggal</small><br />
                                <label>{{date('d-M-Y',strtotime($data->do_date))}}</label>
                                <hr class="py-0 my-0" />
                            @else
                                <label>Tanggal</label>
                                <input type="date" class="form-control" wire:model="do_date" />
                                @error('do_date') <span class="text-danger">{{ $message }}</span> @enderror
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <small>No Purchase Order</small><br />
                            <label>{{$data->no_po}}</label>
                            <hr class="py-0 my-0" />
                        </div>
                        <div class="form-group">
                            <small>Purchase Date</small><br />
                            <label>{{$data->purchase_order_date ? date('d/F/Y',strtotime($data->purchase_order_date)) : '-'}}</label>
                            <hr class="mx-0 my-0" />
                        </div>
                        <div class="form-group">
                            <small>Catatan</small><br />
                            <label>{{$data->catatan}}</label>
                            <hr class="py-0 my-0" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <small>Supplier</small><br />
                            <label>{{isset($data->supplier->nama_supplier) ? $data->supplier->nama_supplier : '-'}}</label>
                            <hr class="mx-0 my-0" />
                        </div>
                        <div class="form-group">
                            <small>Alamat Pengiriman</small><br />
                            <label>{{$data->alamat_penagihan}}</label>
                            <hr class="mx-0 my-0" />
                        </div>
                    </div>
                </div>
                <!-- <h6>Produk</h6> -->
                <hr class="mt-0 pt-0" />
                <div class="table-responsive">
                    <table class="table c_list table-hover table-bordered">
                        <thead>
                            <tr style="background: #eee;">
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Produk</th>
                                <th class="text-center">UOM</th>
                                <th class="text-center">QTY</th>
                                <th class="text-center">Diskon (Rp)</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @php($total=0)
                            @php($total_qty=0)
                            @php($sub_total=0)
                            @foreach($data->details as $k => $item)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{isset($item->product->kode_produksi) ? $item->product->kode_produksi : '-'}}</td>
                                    <td>{{isset($item->product->keterangan) ? $item->product->keterangan : '-'}}</td>
                                    <td class="text-center">
                                        @if($data->status==0)
                                            @livewire('purchase-order.editable',['field'=>'product_uom_id','data'=>(isset($item->uom->name) ? $item->uom->name : ''),'id'=>$item->id],key('product_uom_id'.$item->id))
                                        @else
                                            {{isset($item->uom->name) ? $item->uom->name : '-'}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($data->status==0)
                                            @livewire('purchase-order.editable',['field'=>'qty','data'=>$item->qty,'id'=>$item->id],key('qty'.$item->id))
                                        @else
                                            {{$item->qty}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($data->status==0)
                                            @livewire('purchase-order.editable',['field'=>'disc','data'=>$item->disc,'id'=>$item->id],key('disc'.$item->id))
                                        @else
                                            {{$item->diskon}}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($data->status==0)
                                            @livewire('purchase-order.editable',['field'=>'price','data'=>$item->price,'id'=>$item->id],key('price'.$item->id))
                                        @else
                                            {{format_idr($item->price)}}
                                        @endif
                                    </td>
                                    <td class="text-right">{{format_idr($item->price*$item->qty)}}</td>
                                    <td class="text-center">
                                        @if($data->status==0)
                                            <a href="javascript:void(0)" class="text-danger" wire:click="deleteProduct({{$item->id}})"><i class="fa fa-close"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @php($total += $item->price)
                                @php($total_qty += $item->qty)
                                @php($sub_total += $item->qty * ($item->price-$item->disc))
                            @endforeach
                        </tbody>
                        @if($data->details->count()==0)
                            <tr>
                                <td class="text-center" colspan="7"><i>Data kosong</i></td>
                            </tr>
                        @endif
                        <tfoot style="background: #eee;">
                            <tr>
                                <th colspan="7" class="text-right">Sub Total</th>
                                <th class="text-right">{{format_idr($sub_total)}}</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="7" class="text-right">Biaya Pengiriman</th>
                                <th class="text-right">
                                    @if($data->status==0)
                                        <input type="text" class="form-control text-right" wire:model="biaya_pengiriman" /> 
                                    @else
                                        {{format_idr($data->biaya_pengiriman)}}
                                    @endif
                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="7" class="text-right">Pajak</th>
                                <th class="text-right">
                                    @if($data->status==0)
                                        <input type="text" class="form-control  text-right" wire:model="pajak" />
                                    @else
                                        {{format_idr($data->ppn)}}
                                    @endif
                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="7" class="text-right">Total</th>
                                <th class="text-right">{{format_idr($sub_total+$data->ppn)}}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <hr />
                @if($data->status==4)
                    <div class="form-group">
                        <button type="button" class="btn btn-info" wire:click="submit"><i class="fa fa-check-circle"></i> Submit Delivery Order</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>