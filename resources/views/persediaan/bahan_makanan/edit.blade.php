<div class="row">
    <div class="col-lg-4">
        <label for="">Nama Bahan</label>
        <input type="text" value="{{ $bahan->nm_bahan }}" class="form-control" name="nm_bahan">
        <input type="hidden" value="{{ $bahan->id_list_bahan }}" class="form-control" name="id_bahan">
    </div>
    <div class="col-lg-4">
        <label for="">Kategori</label>
        <select name="kategori_id" id="" class="select2edit">
            <option value="">Pilih Kategori</option>
            @foreach ($kategori as $k)
                <option {{$k->id_kategori_bahan == $bahan->id_kategori ? 'selected' : ''}} value="{{ $k->id_kategori_bahan }}">{{ strtoupper($k->nm_kategori) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2">
        <label for="">Satuan</label>
        <select name="satuan_id" id="" class="select2edit">
            <option value="">Pilih Satuan</option>
            @foreach ($satuan as $s)
                <option {{$s->id_satuan == $bahan->id_satuan ? 'selected' : ''}} value="{{ $s->id_satuan }}">{{ strtoupper($s->nm_satuan) }}</option>
            @endforeach
        </select>
    </div>

</div>