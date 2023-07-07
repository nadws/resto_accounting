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
            <input type="text" id="getPopulasi" readonly name="populasi"
                class="form-control" value="{{ $pop->stok_awal - $pop->pop }}">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Gr Pakan / Ekor</label>
            <input type="text" id="gr" name="grPakanEkor"
                class="form-control pakan_input">
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
            <input type="text" readonly id="krng_s" name="kgKarungSisa"
                class="form-control">
        </div>
    </div>
</div>

<div id="load_pakan_perencanaan"></div>

