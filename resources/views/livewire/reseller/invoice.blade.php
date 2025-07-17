<div>
    <div class="table-responsive" style="min-height:400px;">
        <table class="table table-hover m-b-0 c_list">
            <thead style="background: #eee;">
                <tr>
                    <th class="text-center">No</th>
                    <th>Status</th>
                    <th>No Purchase Order</th>
                    <th>No Invoice</th>
                    <th>Created</th>
                    <th>Due Date</th>
                    <th>Payment Date</th>
                    <th>Metode Pembayaran</th>
                    <th>Total Amount</th>
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
                    </tr>
                    @php($number--)
                @endforeach
            </tbody>
        </table>
    </div>
</div>