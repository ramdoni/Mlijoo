@section('title', 'Delivery Order Reseller')
<div class="row clearfix">
    <div class="col-md-6">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="form-group">
                        <label>{{ __('No Delivery Order') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control"  wire:model="form.no_delivery_order" />
                        <!--<div class="" wire:ignore>
                            <select class="form-control no_delivery_order">
                                <option value=""> -- PILIH -- </option>
                            </select>
                        </div>-->
                        @error('form.no_delivery_order')
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
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Pengirim Nama') }}</label>
                            <input type="text" class="form-control"  wire:model="form.pengirim_nama" >
                            @error('form.pic_nama')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Pengirim No Telepon') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="form.pengirim_no_telepon">
                            @error('form.pengirim_no_telepon')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <a href="{{route('produsen.index')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                        <button wire:ignore.remove wire:target="save" type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                        <span wire:loading  wire:target="save">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <script>
        select_no_delivery_order = $('.no_delivery_order').select2();
        $('.no_delivery_order').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set("form.no_delivery_order", data);
        });
    </script>
@endpush