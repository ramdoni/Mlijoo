<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label>Token</label>
            <textarea class="form-control" wire:model="apps_token"></textarea>
            <small>* Token digunakan untuk sinkron antara sistem kasir, sistem anggota dan sistem online</small>
        </div>
    </div>
    <div class="col-md-5">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Type</label>
                <select class="form-control" wire:model="apps_type">
                    <option value=""> -- Pilih -- </option>
                    <option>Local</option>
                    <option>Online</option>
                </select>
                <small>* Local jika aplikasi hanya di install di local</small><br />
                <small>* Online jika aplikasi hanya di install online</small>
            </div>
            <div class="form-group col-md-6">
                <label>Sinkron</label>
                <select class="form-control" wire:model="apps_sinkron">
                    <option value=""> -- Pilih -- </option>
                    <option>On</option>
                    <option>Off</option>
                </select>
                <small>* Semua aktifitas akan di sinkron ke database online</small>
            </div>
        </div>
        <div class="form-group">
            <label>URL</label>
            <input type="text" class="form-control" wire:model="apps_url" />
            <small>* url yang akan digunakan untuk backup data local ke online</small>
        </div>

    </div>
    <div class="col-md-12">
        <hr />
        <button type="button" class="btn btn-info" wire:click="save"><i class="fa fa-save"></i> Simpan Perubahan</button>
    </div>
</div>
