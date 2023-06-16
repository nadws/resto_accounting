<div class="row">
    <input type="hidden" name="id_kandang" value="{{ $d->id_kandang }}">
    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Tanggal</label>
            <input required value="{{ $d->tgl }}" type="date" name="tgl"
                class="form-control">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Nama Kandang</label>
            <input required type="text" value="{{ $d->nm_kandang }}" name="nm_kandang" class="form-control">
        </div>
    </div>
    <div class="col-lg-6">
        <label for="">Strain</label>
        <select name="strain" class="form-control select2-edit" id="">
            <option value="">- Pilih Strain -</option>
            @php
                $strain = ['isa', 'lohman', 'hisex', 'hyline', 'hovogen'];
            @endphp
            @foreach ($strain as $s)
                <option {{$d->strain == ucwords($s) ? 'selected' : ''}} value="{{ ucwords($s) }}">{{ ucwords($s) }} Brown</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Ayam Awal</label>
            <input required type="text" value="{{ $d->ayam_awal }}" name="ayam_awal" class="form-control">
        </div>
    </div>
</div>