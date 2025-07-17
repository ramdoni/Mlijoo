@section('title', 'Anggota')
@section('sub-title', $data->name .' - '. $data->no_anggota_platinum)
<div class="mt-2 card">
    <div class="card-body">
        <ul class="nav nav-tabs-new2">
            <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#tab_profile">Profile</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_simpanan">Simpanan</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_pinjaman">Pinjaman</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_transaksi">Transaksi</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_log">Log Activity</a></li>
            <!-- <li class="nav-item">
                <a class="nav-item" data-toggle="tab" href="#tab_coopay" style="padding-bottom:17px">
                    <img src="{{asset('assets/img/coopay-1.png')}}" style="height:25px;" />
                </a>
            </li> -->
        </ul>
        <div class="tab-content">
            <div class="tab-pane show active" id="tab_profile">
                <div class="row">
                    <div class="col-md-4">
                        <div class="body">
                            <h6>Profile Photo</h6>
                            <div class="media photo">
                                <div class="media-left m-r-15">
                                    @if($data->pas_foto)
                                        <img src="{{asset($data->pas_foto)}}" style="width:100" />
                                    @else
                                        <i class="fa fa-user-circle-o" style="font-size:80px;"></i>
                                    @endif
                                </div>
                                <div class="media-body">
                                    <p>Upload your photo.
                                        <br> <em>Image should be at least 140px x 140px</em></p>
                                    <button type="button" class="btn btn-default-dark" id="btn-upload-photo">Upload Photo</button>
                                    <input type="file" id="filePhoto" class="sr-only" wire:model="pas_foto">
                                </div>
                            </div>
                            <table class="table table-hover m-b-0 c_list">
                                <tr>
                                    <th>Tipe</th>
                                    <td style="width:10"> : </td>
                                    <td>@livewire('user-member.editable',['field'=>'type','data'=>$data->type,'id'=>$data->id],key('type'.$data->id))</td>
                                </tr>
                                <tr>
                                    <th>No Anggota</th>
                                    <td style="width:10"> : </td>
                                    <td>@livewire('user-member.editable',['field'=>'no_anggota_platinum','data'=>$data->no_anggota_platinum,'id'=>$data->id],key('no_anggota_platinum'.$data->id))</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td style="width:10"> : </td>
                                    <td>@livewire('user-member.editable',['field'=>'name','data'=>$data->name,'id'=>$data->id],key('name'.$data->id))</td>
                                </tr>
                                <tr>
                                    <th>No Telepon</th>
                                    <td style="width:10"> : </td>
                                    <td>@livewire('user-member.editable',['field'=>'phone_number','data'=>$data->phone_number,'id'=>$data->id],key('phone_number'.$data->id))</td>
                                </tr>
                                <tr>
                                    <th>No KTP</th>
                                    <td style="width:10"> : </td>
                                    <td>@livewire('user-member.editable',['field'=>'Id_Ktp','data'=>$data->Id_Ktp,'id'=>$data->id],key('Id_Ktp'.$data->id))</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td style="width:10"> : </td>
                                    <td>@livewire('user-member.editable',['field'=>'jenis_kelamin','data'=>$data->jenis_kelamin,'id'=>$data->id],key('jenis_kelamin'.$data->id))</td>
                                </tr>
                                <tr>
                                    <th>Tempat Lahir</th>
                                    <td style="width:10"> : </td>
                                    <td>@livewire('user-member.editable',['field'=>'tempat_lahir','data'=>$data->tempat_lahir,'id'=>$data->id],key('tempat_lahir'.$data->id))</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td style="width:10"> : </td>
                                    <td>@livewire('user-member.editable',['field'=>'tanggal_lahir','data'=>$data->tanggal_lahir,'id'=>$data->id],key('tanggal_lahir'.$data->id))</td>
                                </tr>
                                <tr>
                                    <th></th>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            {{-- @foreach($data->simpanan as $simpanan)
                                <div class="col-md-3 text-center">
                                    <label>{{isset($simpanan->jenis_simpanan->name) ? $simpanan->jenis_simpanan->name : '-'}}</label>
                                    <h4>{{format_idr($simpanan->amount)}}</h4>
                                </div>
                            @endforeach --}}
<!--                             
                            <div class="col-md-3 text-center">
                                <label>Simpanan Wajib</label>
                                <h4>{{format_idr($data->simpanan_wajib)}}</h4>
                            </div>
                            <div class="col-md-3 text-center">
                                <label>Simpanan Sukarela</label>
                                <h4>{{format_idr($data->simpanan_sukarela)}}</h4>
                            </div>
                            <div class="col-md-3 text-center">
                                <label>Simpanan Lain-lain</label>
                                <h4>{{format_idr($data->simpanan_lainnya)}}</h4>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_simpanan">
                @livewire('user-member.detail-simpanan',['data'=>$data->id])
            </div>
            <div class="tab-pane" id="tab_pinjaman">
                @livewire('user-member.detail-pinjaman',['data'=>$data->id])
            </div>
            <div class="tab-pane" id="tab_transaksi">
                @livewire('user-member.detail-transaksi',['data'=>$data->id])
            </div>
            <div class="tab-pane" id="tab_log">
                @livewire('user-member.detail-log',['data'=>$data->id])
            </div>
        </div>
        <div class="form-group">
            <hr />
            <a href="/"><i class="fa fa-arrow-left"></i> {{__('Kembali')}}</a>
        </div>
    </div>
</div>
@push('after-scripts')
    <script>
        $('#btn-upload-photo').on('click', function() {
            $(this).siblings('#filePhoto').trigger('click');
        });
        Livewire.on('reload',()=>{
            $(".modal").modal('hide');
        });
    </script>
@endpush