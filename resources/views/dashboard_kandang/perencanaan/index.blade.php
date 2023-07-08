<div class="row">
    <div class="col-lg-3">
        <label for="">Tanggal</label>
        <input value="{{ date('Y-m-d') }}" readonly required type="date" name="tgl" id="tglPerencanaan"
            class="form-control">
    </div>
    <div class="col-lg-2">
        <label for="">Kandang</label>
        <input type="hidden" name="id_kandang">
        <input readonly type="text" class="form-control" value="{{ $kandang->nm_kandang }}">

    </div>
    <div class="col-lg-3">
        <label for="">Kg pakan/box</label>
        <input type="text" id="krng" name="kgPakanBox" class="form-control pakan_input">
    </div>
</div>

<hr style="border: 1px solid #6777EF;">
<h5 style="text-decoration: underline">Pakan</h5>

<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Populasi</label>
            <input type="text" id="getPopulasi" readonly name="populasi" class="form-control"
                value="{{ $pop->stok_awal - $pop->pop }}">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Gr Pakan / Ekor</label>
            <input type="text" id="gr" name="grPakanEkor" class="form-control pakan_input">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Kg/karung</label>
            <input type="text" id="krng_f" readonly name="kgKarung" class="form-control">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Kg/karung sisa</label>
            <input type="text" readonly id="krng_s" name="kgKarungSisa" class="form-control">
        </div>
    </div>
</div>
<div id="load_pakan_perencanaan"></div>

<h5 style="text-decoration: underline">Obat/vit dengan campuran pakan</h5>
<div id="load_obat_pakan"></div>

<h5 style="text-decoration: underline">Obat/vit dengan campuran air</h5>
{{-- obat campuran air --}}
<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Obat</label>
            <select name="id_obatAir[]" id="" detail="1" class="form-control select2 id_obat_air">
                <option value="">- Pilih Obat -</option>
                {{-- @foreach ($obat_air as $o)
                    <option value="{{ $o->id_barang }}">{{ $o->nm_barang }}</option>
                @endforeach --}}
            </select>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Dosis</label>
            <input type="text" class="form-control" name="dosisAir[]">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Satuan</label>
            <input type="text" id="stnAir1" readonly name="satuanObatAir[]" class="form-control">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Campuran</label>
            <input type="text" class="form-control" name="obatCampuranAir[]">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Satuan</label>
            <input type="text" id="stncAir1" readonly name="satuanObatAir2[]" class="form-control">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Waktu</label><br>
            <input type="time" name="waktuObat[]" class="form-control">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Cara Pemakaian</label>
            <input type="text" name="caraPemakaian[]" class="form-control">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Keterangan</label>
            <input type="text" name="ket[]" class="form-control">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Aksi</label><br>
            <button class="btn btn-primary btn-md" type="button" id="tbhObatAir"><i
                    class="fa fa-plus"></i></button>
        </div>
    </div>
</div>
<div id="load_obat_air"></div>

<h5 style="text-decoration: underline">Obat/ekor ayam</h5>
{{-- obat ekor ayam --}}
<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Obat</label>
            <select name="id_obatAyam" id="obatAyam" class="form-control select2">
                <option value="">- Pilih Obat -</option>
                {{-- @foreach ($obat_ayam as $o)
                                            <option value="{{ $o->id_barang }}">{{ $o->nm_barang }}</option>
                                        @endforeach --}}
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Dosis</label>
            <input type="text" class="form-control" id="ds1" name="dosisObatAyam">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Satuan</label>
            <input type="text" id="satuanObatAyam" readonly class="form-control" id="stn1"
                name="obatAyamSatuan">
        </div>
    </div>
</div>
{{-- ---------------------- --}}
