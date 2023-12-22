<div class="row">
    <div class="col-lg-4">
        <label for="">Nama akun</label>

        <input type="text" value="{{ $akun->nm_akun }}" class="form-control" name="nm_akun" required>
        <input type="hidden" value="{{ $akun->id_akun }}" name="id_akun">
    </div>
    <div class="col-lg-3">
        <label for="">Inisial</label>
        <input type="text" value="{{ $akun->inisial }}" class="form-control" name="inisial" required>
    </div>
    <div class="col-lg-2">
        <label for="">Nomer akun</label>
        <input type="text" value="{{ $akun->kode_akun }}" class="form-control" name="kode_akun" required>
    </div>
    <div class="col-lg-3">
        <label for="">Kategori</label>
        <select name="id_klasifikasi" id="" class="select-edit" required>
            <option value="">Pilih Kategori</option>
            @foreach ($kategori_akun as $k)
                <option {{ $k->id_subklasifikasi_akun == $akun->id_klasifikasi ? 'selected' : '' }} value="{{ $k->id_subklasifikasi_akun }}">{{ $k->nm_subklasifikasi }}</option>
            @endforeach
        </select>
    </div>
</div>