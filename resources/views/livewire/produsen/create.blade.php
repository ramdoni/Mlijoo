@section('title', 'Tambah Produsen')
<div class="row clearfix">
    <div class="col-md-6">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="form-group">
                        <label>{{ __('Nama Produsen') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control"  wire:model="form.nama" >
                        @error('form.nama')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Alamat') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control"  wire:model="form.alamat" >
                        @error('form.alamat')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Spesifikasi') }}</label>
                        <input type="text" class="form-control"  wire:model="form.spesifikasi" >
                        @error('form.alamat')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>{{ __('PIC Nama') }}</label>
                            <input type="text" class="form-control"  wire:model="form.pic_nama" >
                            @error('form.pic_nama')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('PIC Phone') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="form.pic_phone">
                            @error('form.pic_phone')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label>Status Pajak </label>
                            <select wire:model="form.status_pajak" class="form-control">
                                <option value="1">PKP</option>
                                <option value="2">Non PKP</option>
                            </select>
                            @error('form.status_pajak')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <hr>
                    <a href="{{route('produsen.index')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>