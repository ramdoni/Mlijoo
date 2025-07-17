<div wire:ignore.self class="modal fade" id="modal_submit_simpanan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Simpanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Total Anggota </label>
                        <h4>{{count(array_filter($check_id))}}</h4>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="mr-3">
                            <input type="radio" wire:model="jenis_simpanan_id" value="2">
                            <span><i></i>Simpanan Wajib</span>
                        </label>
                        <label>
                            <input type="radio" wire:model="jenis_simpanan_id" value="3">
                            <span><i></i>Simpanan Sukarela</span>
                        </label>
                        @error('jenis_simpanan_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>        
                    @if($jenis_simpanan_id==2)
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Jumlah (bulan)</label>
                                <select class="form-control" wire:model="bulan"> 
                                    <option value="1">1 Bulan</option> 
                                    <option value="2">2 Bulan</option> 
                                    <option value="3">3 Bulan</option> 
                                    <option value="4">4 Bulan</option> 
                                    <option value="5">5 Bulan</option> 
                                    <option value="6">6 Bulan</option> 
                                    <option value="7">7 Bulan</option> 
                                    <option value="8">8 Bulan</option> 
                                    <option value="9">9 Bulan</option> 
                                    <option value="10">10 Bulan</option> 
                                    <option value="11">11 Bulan</option> 
                                    <option value="12">12 Bulan</option> 
                                </select>
                            </div>
                        </div>
                    @endif
                    @if($jenis_simpanan_id==3)
                        <div class="form-group">
                            <label>Nominal</label>
                            <input type="number" class="form-control" wire:model="amount" />
                            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                <div class="modal-footer">
                    <span wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                    <button wire:loading.remove wire:target="save" type="submit" class="btn btn-info"><i class="fa fa-save"></i> Submit Simpanan</button>
                </div>
            </form>
        </div>
    </div>
</div>  