@section('title', 'Register')
<div class="container">
    <div class="mt-2 card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4>{{getenv('APP_NAME')}}</h4>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="form-auth-small" enctype="multipart/form-data" wire:submit.prevent="submit">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-info">DATA AKUN</h5>
                        <hr />
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Username</label><span class="text-danger">*</span>
                                <input type="text" class="form-control" wire:model="username" />
                            </div>
                            <div class="col-md-4">
                                <label>Password</label><span class="text-danger">*</span>
                                <input type="password" class="form-control" wire:model="password" />
                            </div>
                            <div class="col-md-4">
                                <label>Confirm Password</label><span class="text-danger">*</span>
                                <input type="password" class="form-control" wire:model="passwordConfirmation" />
                            </div>

                            <div class="col-md-4">
                                <label>Nama Lengkap</label><span class="text-danger">*</span>
                                <input type="text" class="form-control" wire:model="fullname" />
                            </div>
                            <div class="col-md-4">
                                <label>Email</label><span class="text-danger">*</span>
                                <input type="email" class="form-control" wire:model="email" />
                            </div>
                            <div class="col-md-4">
                                <label>No. Telepon</label><span class="text-danger">*</span>
                                <input type="tel" class="form-control" wire:model="telepon" />
                            </div>

                            <div class="col-12">
                                <label>KTP</label><span class="text-danger">*</span>
                                <input type="file" class="form-control" wire:model="ktp" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-info">DATA MITRA</h5>
                        <hr />
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Nama Mitra</label><span class="text-danger">*</span>
                                <input type="text" class="form-control" wire:model="resellerName" />
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Provinsi</label>
                                    <div>
                                        <select id="provinsi" class="form-control select2" data-placeholder="Pilih Provinsi">
                                            <option></option>
                                            @foreach($provinsi as $item)
                                            <option value="{{$item->id}}">{{$item->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kabupaten</label>
                                    <div>
                                        <select id="kabupaten" class="form-control select2" data-placeholder="Pilih Kabupaten">
                                            <option></option>
                                            @foreach($kabupaten as $item)
                                            <option value="{{$item->id}}">{{$item->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kecamatan</label>
                                    <div>
                                        <select id="kecamatan" class="form-control select2" data-placeholder="Pilih Kecamatan">
                                            <option></option>
                                            @foreach($kecamatan as $item)
                                            <option value="{{$item->id}}">{{$item->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kelurahan</label>
                                    <div>
                                        <select id="kelurahan" class="form-control select2" data-placeholder="Pilih Kelurahan">
                                            <option></option>
                                            @foreach($kelurahan as $item)
                                            <option value="{{$item->id}}">{{$item->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea class="form-control" wire:model="alamat"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <label>Foto Toko</label><span class="text-danger">*</span>
                                <input type="file" class="form-control" wire:model="storePhoto" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <hr />
                        <a href="{{route('login')}}"><i class="fa fa-arrow-left"></i> {{__('Back')}}</a>
                        <button type="submit" class="ml-3 btn btn-primary pull-right">{{ __('Submit Pendaftaran') }} <i class="fa fa-check"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@push('after-scripts')
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}" />
<script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>

<script>
    const initSelect2 = () => {
        $('.select2').each(function() {
            $(this).select2({
                placeHolder: $(this).data('placeholder')
            });
        })
    }

    document.addEventListener('livewire:load', () => {
        initSelect2();

        Livewire.hook('message.processed', (message, component) => {
            initSelect2();
        });

        $('#provinsi').on('change', function(e) {
            Livewire.emit('onProvinsiChanged', $(this).val());
        });

        $('#kabupaten').on('change', function(e) {
            Livewire.emit('onKabupatenChanged', $(this).val());
        });

        $('#kecamatan').on('change', function(e) {
            Livewire.emit('onKecamatanChanged', $(this).val());
        });

        $('#kelurahan').on('change', function(e) {
            Livewire.emit('onKelurahanChanged', $(this).val());
        });
    });
</script>
@endpush