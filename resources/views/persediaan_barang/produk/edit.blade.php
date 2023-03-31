<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="">Image</label>
            <input type="file" name="img" class="form-control">
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-group">
            <label for="">Nama Produk</label>
            <input value="{{ $produk->nm_produk }}" required type="text" name="nm_produk" class="form-control">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Kode Produk</label>
            <input type="hidden" name="kd_produk" value="{{ $produk->kd_produk }}">
            <input required value="P-{{ kode($produk->kd_produk) }}" readonly type="text"
                class="form-control">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Satuan</label>
            <select required name="satuan_id" class="form-control select2" id="">
                <option value="">- Pilih Satuan -</option>
                @foreach ($satuan as $d)
                    <option {{$d->id_satuan == $produk->satuan_id ? 'selected' : ''}} value="{{ $d->id_satuan }}">{{ $d->nm_satuan }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-12 mb-2">
        <div class="form-group">
            <label for="">Gudang</label>
            <select required name="gudang_id" class="form-control select2" id="">
                <option value="">- Pilih Gudang -</option>
                @foreach ($gudang as $d)
                    <option {{$d->id_gudang == $produk->gudang_id ? 'selected' : ''}} value="{{ $d->id_gudang }}">{{ $d->nm_gudang }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @php
        $cek = $produk->kontrol_stok == 'Y' ? 'checked' : '';
    @endphp
    <x-theme.toggle name="kontrol stok" checked="{{$cek}}" />
</div>