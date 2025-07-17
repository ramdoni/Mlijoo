@section('title', __('Tambah Produk'))

<div class="row clearfix">
    <div class="col-md-6">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="form-group">
                        <label>{{ __('Kode Produksi / Barcode') }}</label>
                        <input type="text" class="form-control" wire:model="barcode" >
                        @error('kode_produksi')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Nama Produk') }}</label>
                        <input type="text" class="form-control" wire:model="nama_product" >
                        @error('description')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <!-- <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control">
                            <option value="1">STOCK</option>
                            <option value="2">KONSINYASI</option>
                        </select>
                        @error('harga')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div> -->
                    <div class="form-group">
                        <label>{{ __('Deskripsi Produk') }}</label>
                        <textarea name="" id="" cols="30" rows="6" class="form-control"  wire:model="desc_product" ></textarea>
                        @error('harga')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Stok') }}</label>
                        <input type="text" class="form-control"  wire:model="qty" >
                        @error('harga')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Harga') }}</label>
                        <input type="text" class="form-control"  wire:model="price" >
                        @error('harga')
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