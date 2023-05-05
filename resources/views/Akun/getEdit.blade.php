<div class="row">
    <input type="hidden" name="id_akun" value="{{ $akun->id_akun }}">
    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Subklasifikasi</label>
            <select name="id_klasifikasi" id="" class="select2-edit get_kode">
                <option value="">Pilih Subklasifikasi</option>
                @foreach ($subklasifikasi as $s)
                    <option {{$s->id_subklasifikasi_akun == $akun->id_klasifikasi ? 'selected' : ''}} value="{{ $s->id_subklasifikasi_akun }}">{{ $s->nm_subklasifikasi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Kode Akun</label>
            <input type="text" value="{{ $akun->kode_akun }}" class="form-control kode" name="kode_akun" required>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-group">
            <label for="">Nama Akun</label>
            <input type="text" value="{{ $akun->nm_akun }}" name="nm_akun" class="form-control">
        </div>
    </div>
</div>
