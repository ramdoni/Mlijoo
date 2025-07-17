@section('title', 'Log Activity')

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="p-4 row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>                             
                                <th>Subject</th>                                    
                                <th>Url</th>                                    
                                <th>Method</th>
                                <th>IP</th>
                                <th>Agent</th>
                                <th>Var</th>
                                <th>Created</th>
                            </tr> 
                        </thead>
                        <tbody>
                            @foreach($data as $k => $item)
                            <tr>
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td>{{$item->subject}}</td> 
                                <td>{{$item->url}}</td>  
                                <td>{{$item->method}}</td>  
                                <td>{{$item->ip}}</td>  
                                <td>{{$item->agent}}</td>  
                                <td>{{$item->var}}</td>  
                                <td>{{$item->created_at}}</td>  
                            </tr>
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