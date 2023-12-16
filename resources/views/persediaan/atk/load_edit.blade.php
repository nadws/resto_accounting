<div class="row">
    <div class="col-lg-2">
        <label for="">CFM</label>
        <input type="text" value="{{ $get->cfm }}" class="form-control" name="cfm">
        <input type="hidden" value="{{ $get->id_atk }}" name="id_atk">
    </div>
    <div class="col-lg-4">
        <label for="">Kategori</label>
        <select name="kategori_id" id="" class="selectEdit">
            <option value="">Pilih Kategori</option>
            @foreach ($kategori as $k)
                <option {{ $get->id_kategori == $k->id_kategori ? 'selected' : '' }} value="{{ $k->id_kategori }}">
                    {{ $k->nm_kategori }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-4">
        <label for="">Nama Atk</label>
        <input type="text" value="{{ $get->nm_atk }}" class="form-control" name="nm_atk">
    </div>
    <div class="col-lg-2">
        <label for="">Satuan</label>
        <select name="satuan_id" id="" class="selectEdit">
            <option value="">Pilih Satuan</option>
            @foreach ($satuan as $s)
                <option {{ $get->id_satuan == $s->id_satuan ? 'selected' : '' }} value="{{ $s->id_satuan }}">
                    {{ $s->nm_satuan }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-4 mt-2">
        <label for="">Upload Foto</label>
        <input type="file" class="form-control" name="foto">
    </div>
    <div class="col-lg-4 mt-2">
        <label>Kontrol Stok</label>
        <br>
        <input type="radio" {{ $get->kontrol_stok == 'Y' ? 'checked' : '' }} class="mt-2" name="kontrol_stok"
            id="" value="Y"> Iya
        &nbsp;
        <input type="radio" {{ $get->kontrol_stok == 'T' ? 'checked' : '' }} class="mt-2" name="kontrol_stok"
            id="" value="T"> Tidak
    </div>
</div>
