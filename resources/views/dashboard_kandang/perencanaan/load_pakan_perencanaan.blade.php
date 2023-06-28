<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Type</label>
            <select name="id_pakan[]" id="" persen="1"
                class="form-control select2-edit persen_pakan pakan_input">
                <option value="">- Pilih Pakan -</option>
                @foreach ($pakan as $p)
                    <option value="{{ $p->id_pakan }}">{{ $p->nm_pakan }}</option>
                @endforeach
                <option value="tambah">+ Pakan</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">%</label>
            <input type="text" id="prsn1" name="persenPakan[]"
                class="form-control pakan_input persen" kd="1">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Pakan (Gr)</label>
            <input type="text" readonly name="pakanGr[]" id="hasil1"
                class="form-control hasil">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Aksi</label><br>
            <button class="btn btn-primary btn-md pakan_input" type="button" id="tbhPakan"><i
                    class="fa fa-plus"></i></button>
        </div>
    </div>
</div>
<div id="detail_pakan"></div>
<div class="row">
    <div class="col-lg-3">
    </div>
    <div class="col-lg-2">
    </div>
    <div class="col-lg-3">
        <hr style="border: 1px solid #6777EF;">
        <input type="text" readonly name="pakanGrTotal" id="total" class="form-control">
    </div>
</div>