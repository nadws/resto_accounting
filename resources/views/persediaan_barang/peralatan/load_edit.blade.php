<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <input type="hidden" name="id_kelompok" value="{{ $d->id_kelompok }}">
            <label for="">Nama Kelompok</label>
            <input value="{{ $d->nm_kelompok }}" required type="text" name="nm_kelompok" class="form-control">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Umur</label>
            <input required value="{{ $d->umur }}" type="text" name="umur" class="form-control">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Peridode</label><br>
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" value="bulan" class="btn-check" name="periode" id="btnradio1"
                    autocomplete="off" {{$d->periode == 'bulan' ? 'checked' : ''}}>
                <label class="btn btn-outline-primary btn-sm" for="btnradio1">Bulan</label>

                <input type="radio" value="tahun" class="btn-check" name="periode"
                    id="btnradio2" autocomplete="off" {{$d->periode == 'tahun' ? 'checked' : ''}}>
                <label class="btn btn-outline-primary btn-sm" for="btnradio2">Tahun</label>

            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-group">
            <label for="">Barang Kelompok</label>
            <input type="text" value="{{ $d->barang_kelompok }}" name="barang_kelompok" class="form-control">
        </div>
    </div>
</div>