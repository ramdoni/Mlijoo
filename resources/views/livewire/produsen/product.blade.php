<div>
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
                    <th class="text-right">Harga</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php($number= $data->total() - (($data->currentPage() -1) * $data->perPage()) )
                @foreach($data as $item)
                    <tr>
                        <td>{{$number}}</td>
                        <td></td>
                        <td></td>
                        <td>{{$item->sku}}</td>
                        <td>{{$item->nama}}</td>
                        <td></td>
                        <td>{{$item->stock}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
