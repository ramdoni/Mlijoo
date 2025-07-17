@section('title', 'Purchase Order')
@section('sub-title', "#".$data->no_po)
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <small>No Purchase Order</small>
                            @if($data->status==0)
                                <span class="badge badge-warning mr-0">Draft</span>
                            @endif
                            @if($data->status==1)
                                <span class="badge badge-success mr-0">Submitted</span>
                            @endif
                            @if($data->status==2)
                                <span class="badge badge-warning mr-0">Waiting for Payment</span>
                            @endif
                            @if($data->status==3)
                                <span class="badge badge-info mr-0">Payment Sent</span>
                            @endif
                            @if($data->status==4)
                                <span class="badge badge-success mr-0">Payment Confirmed</span>
                            @endif
                            @if($data->status==5)
                                <span class="badge badge-danger mr-0">Cancel</span>
                            @endif
                            <br /><label>{{$data->no_po}}</label>
                            <hr class="py-0 my-0" />
                        </div>
                        <div class="form-group">
                            @if($data->status==0)
                                <small>Purchase Date</small>
                                <input type="date" class="form-control" wire:model="purchase_order_date" />
                                @error('purchase_order_date') <span class="text-danger">{{ $message }}</span> @enderror
                            @else
                                <small>Purchase Date</small><br />
                                <label>{{$data->purchase_order_date ? date('d/M/Y',strtotime($data->purchase_order_date)) : '-'}}</label>
                                <hr class="py-0 my-0" />
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <small>Issued Date</small><br />
                            <label>{{$data->submitted_date ? date('d/M/Y',strtotime($data->submitted_date)) : '-'}}</label>
                            <hr class="py-0 my-0" />
                        </div>
                        <div class="form-group">   
                            @if($data->status==0)
                                <small>Produsen</small> 
                                <div wire:ignore>
                                    <select class="form-control produsen_id">
                                        <option value=""> -- Pilih -- </option>
                                        @foreach($produsens as $item)
                                            <option value="{{$item->id}}">{{$item->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('form.produsen_id') <span class="text-danger">{{ $message }}</span> @enderror
                            @else
                                <small>Produsen</small><br />
                                <label>{{isset($data->produsen->nama) ? $data->produsen->nama : ''}}</label>
                                <hr class="" />
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        @if($data->status==0)
                            <small>Alamat Pengiriman</small>
                            <input type="text" class="form-control" wire:model="alamat_penagihan" />
                            @error('alamat_penagihan') <span class="text-danger">{{ $message }}</span> @enderror
                        @else
                            <small>Alamat Pengiriman</small><br />
                            <label>{{$data->alamat_penagihan}}</label>
                            <hr class="py-0 my-0" />
                        @endif
                    </div>
                </div>
                <!-- <h6>Produk</h6> -->
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
                            @if($data->status==0)
                                <tr>
                                    <td></td>
                                    <td colspan="2">
                                        <div wire:ignore>
                                            <select class="form-control select_product_po">
                                                <option value=""> -- Barcode / Produk -- </option>
                                                @foreach($list_product_supplier as $item)
                                                <option value="">{{$item->nama_product}}</option>
                                                @endforeach
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
                                        <input type="number" class="form-control text-center" wire:model="qty" min="0" />
                                        @error('qty') <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <input type="number" class="form-control text-right" wire:model="disc" min="0" />
                                        @error('diskon') <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <input type="number" class="form-control text-right" min="0" wire:model="price" />
                                        @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>{{(($price && $qty) ? format_idr($price*$qty) : '')}}</td>
                                    <td>
                                        <span wire:loading wire:target="addProduct">
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">{{ __('Loading...') }}</span>
                                        </span>
                                        <a href="javascript:void(0)" wire:loading.remove wire:target="addProduct" class="btn btn-info btn-sm" wire:click="addProduct"><i class="fa fa-plus"></i></a>
                                    </td>
                                </tr>
                            @endif
                            @php($total=0)
                            @php($total_qty=0)
                            @php($sub_total=0)
                            @foreach($data->details as $k => $item)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{isset($item->product->kode_produksi) ? $item->product->kode_produksi : '-'}}</td>
                                    <td><a href="{{route('product.detail',$item->product_id)}}" target="_blank">{{isset($item->product->keterangan) ? $item->product->keterangan : '-'}}</a></td>
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
                                @php($sub_total += $item->qty * ($item->price-($item->disc ? $item->disc : 0)))
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
                                <td colspan="7" class="text-right">
                                    <label>Pajak</label><br />
                                </td>
                                <th class="text-right">
                                    @if($data->status==0)
                                        <label style="font-weight: normal;">Percentage ({{get_setting('po_pajak')}}%) <input type="radio" wire:model="type_pajak" value="1"/></label><br />
                                        <label style="font-weight: normal;" class="ml-2">Manual <input type="radio" wire:model="type_pajak" value="2" /></label><br />
                                        @if($type_pajak==2)
                                            <input type="text" class="form-control  text-right" wire:model="pajak" placeholder="Rp." />
                                        @else
                                            <label>{{format_idr($pajak)}}</label>
                                        @endif
                                    @else
                                        {{format_idr($data->ppn)}}
                                    @endif
                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="7" class="text-right">Total</th>
                                <th class="text-right">{{format_idr($sub_total+$biaya_pengiriman+$pajak)}}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    @if($data->status==0)
                        <div>
                            <label>Catatan</label> 
                            <textarea class="form-control" wire:model="catatan"></textarea>
                        </div>
                    @else
                        <small>Catatan</small><br />
                        <label>{{$data->catatan}}</label>
                    @endif
                </div>
                <hr />
                <div class="form-group">
                    <a href="{{route('purchase-order.index')}}" class="mr-3"><i class="fa fa-arrow-left"></i> Kembali</a>
                    <span wire:loading wire:target="saveAsDraft,submit">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                    @if($data->status==0)
                        <button type="button" class="btn btn-warning" wire:loading.remove wire:target="saveAsDraft,submit" wire:click="saveAsDraft"><i class="fa fa-save"></i> Save as Draft</button>
                        <button type="button" class="btn btn-info" wire:loading.remove wire:target="saveAsDraft,submit" wire:click="submit"><i class="fa fa-check-circle"></i> Issued</button>
                    @endif
                    @if($data->status==1)
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal_revision"><i class="fa fa-edit"></i> Revision</button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal_cancel"><i class="fa fa-close"></i> Cancel</button>
                    @endif
                    @if($data->status==2)
                        <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_upload_bukti_pembayaran"><i class="fa fa-upload"></i> Pay</a>
                    @endif
                    @if($data->status==3 || $data->status==4 || $data->status==5)
                        <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_upload_bukti_pembayaran"><i class="fa fa-eye"></i> Detail Invoice</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modal_upload_bukti_pembayaran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            @if($data->status==2)
                <div class="row">
                    <div class="col-md-6">
                        <form wire:submit.prevent="sendpayment">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-upload"></i> Upload Bukti Pembayaran</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true close-btn">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Tanggal Pembayaran</label>
                                    <input type="text" class="form-control" wire:model="payment_date" value="<?php echo date('Y-m-d'); ?>"/>
                                    @error('payment_date') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Bayar</label> @if($sisa_bayar_inv > 0)<span style="color: red">(Sisa Bayar : Rp, {{ format_idr($sisa_bayar_inv) }})</span>@endif
                                    <input type="text" class="form-control" wire:model="payment_amount" value="{{ $sisa_bayar_inv }}" />
                                    @error('payment_amount') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Bukti Pembayaran</label>
                                    <input type="file" class="form-control" wire:model="file_bukti_pembayaran" />
                                    @error('file_bukti_pembayaran') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Metode Pembayaran</label>
                                    <select class="form-control" wire:model="metode_pembayaran">
                                        <option value=""> -- Pilih -- </option>
                                        <option value="4">Tunai</option>
                                        <option value="9">Transfer</option>
                                    </select>
                                    @error('file_bukti_pembayaran') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <span wire:loading wire:target="bayar">
                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    <span class="sr-only">{{ __('Loading...') }}</span>
                                </span>
                                <button wire:loading.remove wire:target="sendpayment" type="submit" class="btn btn-info"><i class="fa fa-check-circle"></i> Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                @endif
                
                <div class="col-md-12">
                    <div class="card">
                        <div class="body">
                            <h6>Detail Pembayaran Invoice</h6>
                            <hr />
                            <div class="table-responsive">
                                <table class="table">
                                    <tr style="background: #eee;">
                                        <th>No</th>
                                        <th>Status</th>
                                        <th>Bukti Pembayaran</th>
                                        <th>Jumlah Bayar</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Tanggal Pembayaran</th>
                                    </tr>
                                    @foreach($data_invoice as $k => $item)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>
                                                @if($item->status==1)
                                                    <span class="badge badge-info mr-0">PO Request</span>
                                                @endif
                                                @if($item->status==2)
                                                    <span class="badge badge-warning mr-0">Invoice Sent</span>
                                                @endif
                                                @if($item->status==3)
                                                    <span class="badge badge-warning mr-0">Waiting for Confirmation</span>
                                                @endif
                                                @if($item->status==4)
                                                    <span class="badge badge-success mr-0">Payment Confirm</span>
                                                @endif
                                                @if($item->status==5)
                                                    <span class="badge badge-success mr-0">Deliver</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="" target="_blank"><i class="fa fa-picture-o"></i></a>
                                            </td>
                                            <td>Rp,{{format_idr($item->amount)}}</td>
                                            <td>{{ $item->metode_pembayaran == 4 ? "Tunai" : "Transfer" }}</td>
                                            <td >{{ date_format(date_create($item->created_at), 'd M Y') }}</td>
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

    <div wire:ignore.self class="modal fade" id="modal_cancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="cancel">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-info"></i> Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Cancel Purchase Order ? </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span wire:loading wire:target="cancel">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <button wire:loading.remove wire:target="cancel" type="submit" class="btn btn-info"><i class="fa fa-check-circle"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modal_revision" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="revision">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-info"></i> Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Revision Purchase Order ? </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span wire:loading wire:target="cancel">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <button wire:loading.remove wire:target="revision" type="submit" class="btn btn-info"><i class="fa fa-check-circle"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <script>

        select_produsen = $('.produsen_id').select2();
        $('.produsen_id').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set("form.produsen_id", data);
        });
        setTimeout(()=>{
            $('.produsen_id').val({{$form['produsen_id']}}).trigger('change');
        },1000)
        
        setTimeout(() => {
            select__2 = $('.select_anggota').select2();
            $('.select_anggota').on('change', function (e) {
                var data = $(this).select2("val");
                @this.set("user_member_id", data);
            });
        }, 1000);

        select_product_po = $('.select_product_po').select2({
            placeholder: " -- Barcode / Product -- ",
            data : {!!json_encode($data_product)!!},
            tokenSeparators: [',', '.','/']
        });
        $('.select_product_po').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set("product_id", data);
        });
    </script>
@endpush