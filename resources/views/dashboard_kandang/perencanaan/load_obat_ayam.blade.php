<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Obat</label>
            <select name="id_obat_ayam" id="obatAyam" class="form-control select2-edit obat_ayam_input">
                <option value="">- Pilih Obat -</option>
                @foreach ($pakan as $o)
                    <option value="{{ $o->id_produk }}">{{ $o->nm_produk }}</option>
                @endforeach
                <option value="tambah">+ Obat Baru</option>
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Dosis</label>
            <input type="text" class="form-control" id="ds1" name="dosis_obat_ayam">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Satuan</label>
            <input type="text" readonly class="form-control get_dosis_satuan_ayam"
                name="satuan_obat_ayam">
        </div>
    </div>
</div>