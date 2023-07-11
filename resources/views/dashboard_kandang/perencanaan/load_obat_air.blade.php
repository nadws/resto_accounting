<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Obat</label>
            <select name="id_obat_air[]" count="1" id="" detail="1" class="form-control select2-edit obat_air_input">
                <option value="">- Pilih Obat -</option>
                @foreach ($pakan as $o)
                    <option value="{{ $o->id_produk }}">{{ $o->nm_produk }}</option>
                @endforeach
                <option value="tambah">+ Obat</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Dosis</label>
            <input type="text" class="form-control" name="dosis_obat_air[]">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Satuan</label>
            <input type="text" readonly name="satuan_obat_air[]" class="form-control get_dosis_satuan_air1">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Campuran</label>
            <input type="text" class="form-control" name="campuran_obat_air[]">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Satuan</label>
            <input type="text" id="stncAir1" readonly name="satuan_obat_air[]" class="form-control get_campuran_satuan_air1">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Waktu</label><br>
            <input type="time" name="waktu_obat_air[]" class="form-control">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Cara Pemakaian</label>
            <input type="text" name="cara_pemakaian_obat_air[]" class="form-control">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Keterangan</label>
            <input type="text" name="ket_obat_air[]" class="form-control">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Aksi</label><br>
            <button class="btn btn-primary btn-md tbhObatAir" type="button"><i
                    class="fa fa-plus"></i></button>
        </div>
    </div>
</div>
<div id="tbhObatAir"></div>