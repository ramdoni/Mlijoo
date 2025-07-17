<div>
    <div class="row">
        <div class="col-md-2 form-group">
            <input type="text" class="form-control" placeholder="Barcode / Nama Produk">
        </div>
        <div class="col-md-2 form-group">
            <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_add_product"><i class="fa fa-plus"></i> Tambah</a>
        </div>
    </div>
    <div class="table-responsive" style="min-height:400px;">
        <table class="table table-hover m-b-0 c_list">
            <thead style="background: #eee;">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Type</th>
                    <th>Kode Produksi / SKU</th>
                    <th>Produk</th>
                    <th>UOM</th>
                    <th class="text-center">Stok</th>
                    <th class="text-right">Harga Beli</th>
                    <th class="text-right">Harga Jual</th>
                    <th class="text-right">Margin</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php($number= $data->total() - (($data->currentPage() -1) * $data->perPage()) )
                @foreach($data as $item)
                    <tr>
                        <td>{{$number}}</td>
                        <td>
                            @if($item->status==1)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td></td>
                        <td>{{$item->sku}}</td>
                        <td>{{$item->nama}}</td>
                        <td></td>
                        <td>{{$item->stock}}</td>
                        <td class="text-right">{{format_idr($item->harga_beli)}}</td>
                        <td class="text-right">{{format_idr($item->harga_jual)}}</td>
                        <td class="text-right">{{format_idr($item->margin)}}</td>
                    </tr>
                    @php($number--)
                @endforeach
            </tbody>
        </table>
    </div>
</div>