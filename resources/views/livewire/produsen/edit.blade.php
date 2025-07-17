@section('title', $data->nama)
@section('sub-title', 'Produsen')
<div class="card">
    <!-- <div class="header row">
    </div> -->
    <div class="body">
        <ul class="nav nav-tabs-new2">
            <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#tab-produsen">Produsen</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-product">Product</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-purchase-order">Purchase Order</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-delivery-order">Delivery Order</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-invoice">Invoice</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane show active" id="tab-produsen">
                <form id="basic-form" method="post" wire:submit.prevent="update">
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
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{ __('Simpan Perubahan') }}</button>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="tab-product">
                @livewire('produsen.product',['id'=>$data->id])
            </div>
            <div class="tab-pane" id="tab-purchase-order">
                @livewire('produsen.purchase-order')
            </div>
            <div class="tab-pane" id="tab-delivery-order">
                @livewire('produsen.delivery-order')
            </div>
            <div class="tab-pane" id="tab-invoice">
                @livewire('produsen.invoice',['id'=>$data->id])
            </div>
        </div>
    </div>
</div>
