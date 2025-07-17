<div wire:ignore.self class="modal fade" id="modal_setting_simpanan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-gear"></i> Pengaturan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body row">
                    @if($msg)
                        <div class="col-md-12">
                            <div class="alert alert-success">{{$msg}}</div>
                        </div>
                        <div class="clear mb-3"></div>
                    @endif
                    <div class="form-group col-md-6">
                        <label>Simpanan Pokok</label>
                        <input type="number" class="form-control text-right" wire:model="simpanan_pokok" />
                        <small>impanan yang disertakan anggota pada awal anggota tersebut masuk menjadi anggota. Simpanan pokok ini dibayarkan satu kali</small>
                    </div>                        
                    <div class="form-group col-md-6">
                        <label>Simpanan Wajib</label>
                        <input type="number" class="form-control text-right" wire:model="simpanan_wajib" />
                        <small>simpanan yang harus dibayar oleh anggota setiap bulan</small>
                    </div>     
                    <div class="form-group col-md-6">
                        <label>Bunga Pertahun (%)</label>
                        <input type="text" class="form-control" wire:model="bunga_pertahun_simpanan_sukarela" />
                        @error('bunga_pertahun_simpanan_sukarela')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>