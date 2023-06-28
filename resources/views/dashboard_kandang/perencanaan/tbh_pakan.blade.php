<div class="row baris{{$count}}">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Type</label>
            <select name="id_pakan[]" id="" persen="1"
                count="{{ $count }}" class="form-control select2-pakan persen_pakan pakan_input">
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
            <label for="">Stok</label>
            <input type="text" name="stok" readonly class="form-control get_stok_pakan{{$count}}">
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
            <button count="{{$count}}" class="remove_baris btn btn-danger btn-md" type="button"><i
                    class="fa fa-minus"></i></button>
        </div>
    </div>
</div>