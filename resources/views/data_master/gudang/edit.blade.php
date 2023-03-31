
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <input type="hidden" name="id_gudang" value="{{ $gudang->id_gudang }}">
                    <label for="">Kode Gudang</label>
                    <input required type="text" value="{{ $gudang->kd_gudang }}" name="kd_gudang" class="form-control">
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    <label for="">Nama Gudang</label>
                    <input required type="text" value="{{ $gudang->nm_gudang }}" name="nm_gudang" class="form-control">
                </div>
            </div>
        </div>