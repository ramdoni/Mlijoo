@section('title', __('Tambah Produk'))

<div class="row clearfix">
    <div class="col-md-6">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="form-group">
                        <label>{{ __('Kode Produksi / Barcode') }}</label>
                        <input type="text" class="form-control" wire:model="kode_produksi" >
                        @error('kode_produksi')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Nama Produk') }}</label>
                        <input type="text" class="form-control" wire:model="keterangan" >
                        @error('keterangan')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control" wire:model="type">
                            <option value=""> -- Pilih -- </option>
                            @foreach(config('product.categories') as $item)
                                <option value="{{ $item['id'] }}">{{$item['name']}}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Foto') }}</label>
                        <input type="file" class="form-control" wire:model="file" >
                        @if ($file)
                            <div class="form-group mt-2">
                                <label for="">Preview</label><br />
                                <img src="{{$file->temporaryUrl()}}" width="100">
                            </div>
                        @endif
                        @error('file')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <hr>
                    <a href="javascript:void(0)" onclick="history.back();"><i class="fa fa-arrow-left"></i> {{ __('Kembali') }}</a>
                    <button type="submit" class="btn btn-primary ml-3"><i class="fa fa-save"></i> {{ __('Simpan Produk') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>