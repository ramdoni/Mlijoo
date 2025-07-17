@section('title', 'Simpanan')
@section('sub-title', 'Index')
<div class="clearfix row">
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <img src="{{asset('assets/images/icon/wallet.png')}}" />
                </div>
                <div class="content">
                    <div class="text text-info">Simpanan Pokok</div>
                    <h5 class="number">{{format_idr($simpanan_pokok)}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <img src="{{asset('assets/images/icon/wallet.png')}}" />
                </div>
                <div class="content">
                    <div class="text text-success">Simpanan Wajib</div>
                    <h5 class="number">{{format_idr($simpanan_wajib)}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <img src="{{asset('assets/images/icon/wallet.png')}}" />
                </div>
                <div class="content">
                    <div class="text text-warning">Simpanan Sukarela</div>
                    <h5 class="number">{{format_idr($simpanan_sukarela)}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <img src="{{asset('assets/images/icon/wallet.png')}}" />
                </div>
                <div class="content">
                    <div class="text text-danger">Simpanan Lain-lain</div>
                    <h5 class="number">{{format_idr($simpanan_lain_lain)}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Pencarian" />
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model="status">
                        <option value=""> --- Status --- </option>
                        <option value="1">Aktif</option>
                        <option value="4">Resign</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="javascript:void(0);" onclick="alert('Fitur masih dalam pengembangan')"><i class="fa fa-download"></i> Download</a>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="float-right btn btn-warning" data-toggle="modal" data-target="#modal_setting_simpanan"><i class="fa fa-gear"></i> Pengaturan</a>
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
                                <th>
                                    @if(count(array_filter($check_id))>0)
                                        <p>
                                            <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_submit_simpanan" wire:click="assignCheckId"><i class="fa fa-check-circle"></i> Bayar Simpanan</a>
                                        </p>    
                                    @endif
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" value="1" wire:model="check_all" wire:click="check_all_">
                                        <span> Check All</span>
                                    </label>
                                </th>
                                <th>No Anggota</th>
                                <th>Nama</th>
                                <th class="text-center" style="background:#16a3b83d">Januari</th>
                                <th class="text-center" style="background:#16a3b83d">Februari</th>
                                <th class="text-center" style="background:#16a3b83d">Maret</th>
                                <th class="text-center" style="background:#16a3b83d">April</th>
                                <th class="text-center" style="background:#16a3b83d">Mei</th>
                                <th class="text-center" style="background:#16a3b83d">Juni</th>
                                <th class="text-center" style="background:#16a3b83d">Juli</th>
                                <th class="text-center" style="background:#16a3b83d">Agustus</th>
                                <th class="text-center" style="background:#16a3b83d">September</th>
                                <th class="text-center" style="background:#16a3b83d">Oktober</th>
                                <th class="text-center" style="background:#16a3b83d">November</th>
                                <th class="text-center" style="background:#16a3b83d">Desember</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($anggota as $k => $item)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td class="text-center">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" value="{{$item->id}}" wire:model="check_id.{{$item->id}}">
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>{{$item->no_anggota_platinum}}</td>
                                    <td><a href="{{route('user-member.edit',$item->id)}}">{{$item->name}}</a></td>
                                    @if($item->simpananWajib)
                                        @foreach(['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'] as $month)
                                            <td class="text-center">
                                                @if($item->simpananWajib->$month)
                                                    <a href="javascript:void(0)" class="text-success"><i class="fa fa-check-circle"></i></a>
                                                @else
                                                    <a href="javascript:void(0)" class="text-danger"><i class="fa fa-close"></i></a>
                                                @endif
                                            </td>
                                        @endforeach
                                    @else
                                        @for($i=0;$i<12;$i++)
                                            <td class="text-center">
                                                <a href="javascript:void(0)" class="text-danger"><i class="fa fa-close"></i></a>
                                            </td>
                                        @endfor
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@livewire('user-simpanan.setting')
@livewire('user-simpanan.insert')
