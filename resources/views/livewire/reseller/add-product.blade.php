<div wire:ignore.self class="modal fade" id="modal_add_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Produk</label>
                        <div wire:ignore>
                            <select class="form-control" id="product_id">
                                <option value=""> -- PILIH RODUK -- </option>
                                @foreach($products as $item)
                                    <option value="{{ $item->id }}">{{ $item->kode_produksi }} / {{ $item->keterangan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if($product)
                        <div class="form-group">
                            <label for="">UOM</label>
                            <select wire:model="form.product_uom_id" class="form-control">
                                <option value=""> -- PILIH UOM</option>
                                @foreach($product_uoms as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Harga Beli</label>
                            <input type="text" class="form-control" wire:model="harga_beli" readonly />
                        </div>
                        <div class="form-group">
                            <label for="">Harga Jual</label>
                            <input type="text" class="form-control" wire:model="form.harga_jual" />
                        </div>
                        <div class="form-group">
                            <label for="">Margin</label>
                            <input type="text" class="form-control" wire:model="form.margin" readonly />
                        </div>
                        <div class="form-group">
                            <label for="">Stock</label>
                            <input type="text" class="form-control" wire:model="form.stock" />
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
                    <span wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
@push('after-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <style>
        .select2-container .select2-selection--single {height:36px;padding-left:10px;}
        .select2-container .select2-selection--single .select2-selection__rendered{padding-top:1px;}
        .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
        .select2-container {width: 100% !important;}
    </style>
    <script>
        var select_anggota = $('#product_id').select2({
                placeholder: " -- PILIH PRODUK -- ",
            }
        );
        $('#product_id').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('form.product_id',data);
        });        
    </script>
@endpush